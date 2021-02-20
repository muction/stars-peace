<?php
/**
 * ------------------------------------------------------
 *
 * ------------------------------------------------------
 */

namespace Stars\Peace\Plugs\Cloud\Storage\OSS;

use OSS\Core\OssException;
use OSS\Core\OssUtil;
use OSS\OssClient;

use Stars\Peace\Contracts\Cloud\Storage\CloudStorageBucketInterface;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageInterface;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageObject;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageOptionInterface;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageSTSInterface;

class OssCloudStorage implements CloudStorageBucketInterface, CloudStorageObject, CloudStorageInterface
{
    /**
     * @var null
     */
    private $cloudInstance = null;

    /**
     * 设置配置项
     * @var array
     */
    private $cloudOption;

    /**
     * 构造
     * CosCloudStorage constructor.
     * @param CloudStorageOptionInterface $cloudStorageOption
     */
    public function __construct(CloudStorageOptionInterface $cloudStorageOption)
    {
        $this->cloudOption = $cloudStorageOption;
    }

    /**
     * @inheritDoc
     */
    public function cloudInstance(CloudStorageSTSInterface $cloudStorageSTS)
    {
        $key = $cloudStorageSTS->makeUploadKey($this->cloudOption);
        $credentials = $key['Credentials'];
        $this->cloudInstance = new OssClient(
            $credentials['AccessKeyId'],
            $credentials['AccessKeySecret'],
            $this->cloudOption->getOption('OSS_ENDPOINT'),
            false, $credentials['SecurityToken']
        );
        return $this;
    }


    /**
     * 获取默认Bucket
     * @param null $bucket
     * @return mixed
     */
    private function getDefaultBucket($bucket = null)
    {
        return $bucket == null ? $this->cloudOption->getOption('OSS_BUCKET') : $bucket;
    }

    /**
     * 单文件上传
     * @param $localFile
     * @param $saveFile
     * @param null $bucket
     * @return mixed
     * @link https://help.aliyun.com/document_detail/88473.html?spm=a2c4g.11186623.6.1251.7d126775sMJ3Lu
     */
    public function simpleUpload($localFile, $saveFile, $bucket = null)
    {
        $bucket = $this->getDefaultBucket($bucket);
        return $this->cloudInstance->uploadFile($bucket, $saveFile, $localFile);
    }

    /**
     * 分片上传
     * @param $localFile
     * @param $saveFile
     * @param $bucket
     * @param float $size
     * @return false|mixed|void
     */
    public function sliceUpload($localFile, $saveFile, $bucket, $size = 10)
    {
        $bucket = $this->getDefaultBucket($bucket);
        $uploadId = $this->cloudInstance->initiateMultipartUpload($bucket, $saveFile);
        $partSize = $size * 1024 * 1024;
        $uploadFileSize = filesize($localFile);
        $pieces = $this->cloudInstance->generateMultiuploadParts($uploadFileSize, $partSize);
        $responseUploadPart = array();
        $uploadPosition = 0;
        $isCheckMd5 = true;
        foreach ($pieces as $i => $piece) {
            $fromPos = $uploadPosition + (integer)$piece[$this->cloudInstance::OSS_SEEK_TO];
            $toPos = (integer)$piece[$this->cloudInstance::OSS_LENGTH] + $fromPos - 1;
            $upOptions = array(
                // 上传文件。
                $this->cloudInstance::OSS_FILE_UPLOAD => $localFile,
                // 设置分片号。
                $this->cloudInstance::OSS_PART_NUM => ($i + 1),
                // 指定分片上传起始位置。
                $this->cloudInstance::OSS_SEEK_TO => $fromPos,
                // 指定文件长度。
                $this->cloudInstance::OSS_LENGTH => $toPos - $fromPos + 1,
                // 是否开启MD5校验，true为开启。
                $this->cloudInstance::OSS_CHECK_MD5 => $isCheckMd5,
            );
            // 开启MD5校验。
            if ($isCheckMd5) {
                $contentMd5 = OssUtil::getMd5SumForFile($localFile, $fromPos, $toPos);
                $upOptions[$this->cloudInstance::OSS_CONTENT_MD5] = $contentMd5;
            }
            try {
                // 上传分片。
                $responseUploadPart[] = $this->cloudInstance->uploadPart($bucket, $saveFile, $uploadId, $upOptions);
            } catch (OssException $e) {
                throw $e;
            }
        }

        $uploadParts = array();
        foreach ($responseUploadPart as $i => $eTag) {
            $uploadParts[] = array(
                'PartNumber' => ($i + 1),
                'ETag' => $eTag,
            );
        }

        /**
         * 步骤3：完成上传。
         */
        try {

            return $this->cloudInstance->completeMultipartUpload($bucket, $saveFile, $uploadId, $uploadParts);
        } catch (OssException $e) {
            throw $e;
        }
    }

    /**
     * 文件是否存在
     * @param $object
     * @param $bucket
     * @return mixed
     */
    public function hasObject($object, $bucket)
    {
        return $this->cloudInstance->doesObjectExist($bucket, $object);
    }

    /**
     * 签名一个文件
     * @param $object
     * @param $bucket
     * @param $expiredTime
     * @return mixed
     * @link https://help.aliyun.com/document_detail/32106.html?spm=a2c4g.11186623.6.1289.d8d8668ecB5xyQ
     */
    public function signObject($object, $bucket, $expiredTime)
    {
        return $this->cloudInstance->signUrl($bucket, $object, $expiredTime);
    }
}
