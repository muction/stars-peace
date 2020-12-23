<?php

namespace Stars\Peace\Plugs\Cloud\Storage\COS;

use Illuminate\Support\Facades\Log;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageBucketInterface;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageInterface;
use \Qcloud\Cos\Client;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageObject;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageOptionInterface;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageSTSInterface;

/**
 * ------------------------------------------------------
 *  对象云存储 - 腾讯云
 * ------------------------------------------------------
 */
class CosCloudStorage implements CloudStorageBucketInterface, CloudStorageObject, CloudStorageInterface
{
    /**
     * @var \Qcloud\Cos\Client $cloudInstance
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
     * 单文件本地上传
     * @param $localFile
     * @param $saveFile
     * @param $bucket
     * @return mixed|void
     * @link https://cloud.tencent.com/document/product/436/34282#.E7.AE.80.E5.8D.95.E4.B8.8A.E4.BC.A0.E5.AF.B9.E8.B1.A1
     */
    public function simpleUpload($localFile, $saveFile, $bucket = null)
    {
        if ($f = fopen($localFile, 'rb')) {
            $option = [
                'Bucket' => $this->getDefaultBucket($bucket),
                'Key' => $saveFile,
                'Body' => $f
            ];
            return $this->cloudInstance->putObject($option);
        }
        return false;
    }

    /**
     * TODO 分片上传
     * @param $localFile
     * @param $saveFile
     * @param $bucket
     * @param float $size
     * @return mixed|void
     * @link https://cloud.tencent.com/document/product/436/34282#.3Cspan-id-.3D-.22init_mulit_upload.22.3E-.E5.88.9D.E5.A7.8B.E5.8C.96.E5.88.86.E5.9D.97.E4.B8.8A.E4.BC.A0-.3C.2Fspan.3E
     */
    public function sliceUpload($localFile, $saveFile, $bucket, $sliceSize = 1000000)
    {
        $parts = [];
        $uploadId = null;
        $bucket = $this->getDefaultBucket($bucket);
        try {

            $initMultipartUpload = $this->cloudInstance->createMultipartUpload(
                ['Bucket' => $bucket, //格式：BucketName-APPID
                    'Key' => $saveFile]);
            $uploadId = $initMultipartUpload['UploadId'];
            $fileSize = filesize($localFile);
            $allowParts = ceil($fileSize / $sliceSize);
            for ($i = 1; $i <= $allowParts; $i++) {
                $offset = ($i-1)*$sliceSize;
                $result = $this->cloudInstance->uploadPart(array(
                    'Bucket' => $bucket, //格式：BucketName-APPID
                    'Key' => $saveFile,
                    'Body' => file_get_contents($localFile, null, null, $offset, $sliceSize),
                    'UploadId' => $uploadId, //UploadId 为对象分块上传的 ID，在分块上传初始化的返回参数里获得
                    'PartNumber' => $i, //PartNumber 为分块的序列号，COS 会根据携带序列号合并分块
                ));

                $parts[] = [
                    'ETag' => $result['ETag'],
                    'PartNumber' => $i
                ];
            }

            $completeMultipartUpload = $this->cloudInstance->completeMultipartUpload([
                'Bucket' => $bucket, //格式：BucketName-APPID
                'Key' => $saveFile,
                'UploadId' => $uploadId,
                'Parts' => $parts
            ]);

            return $saveFile;

        } catch (\Exception $exception) {
            //上传异常，终止上传操作
            if ($uploadId) {
                $result = $this->cloudInstance->abortMultipartUpload(array(
                    'Bucket' => $bucket, //格式：BucketName-APPID
                    'Key' => $saveFile,
                    'UploadId' => $uploadId,
                ));
            }
            throw $exception;
        }
    }

    /**
     * 判断是否有某个对象
     * @param $object
     * @param $bucket
     * @return mixed|void
     * @link https://cloud.tencent.com/document/product/436/34282#.E6.9F.A5.E8.AF.A2.E5.AF.B9.E8.B1.A1.E5.85.83.E6.95.B0.E6.8D.AE
     */
    public function hasObject($object, $bucket = null)
    {
        return $this->cloudInstance->doesObjectExist(
            $this->getDefaultBucket($bucket), $object);
    }


    /**
     * 签名一个object
     * @param $bucket
     * @param $object
     * @param $expiredTime
     * @return mixed|void
     * @link https://cloud.tencent.com/document/product/436/34284#.E4.B8.8B.E8.BD.BD.E8.AF.B7.E6.B1.82.E7.A4.BA.E4.BE.8B2
     */
    public function signObject($object, $expiredTime, $bucket = null)
    {
        return $this->cloudInstance->getObjectUrl(
            $this->getDefaultBucket($bucket),
            $object,
            '+' . $expiredTime . ' minutes'
        );
    }

    /**
     * 获取COS操作对象
     * @param CloudStorageSTSInterface $cloudStorageSTS
     * @return $this|mixed
     */
    public function cloudInstance(CloudStorageSTSInterface $cloudStorageSTS)
    {
        $cloudStorageSTS = $cloudStorageSTS->makeUploadKey($this->cloudOption);
        $clientOption = [
            'region' => $this->cloudOption->getOption('COS_REGION'),
            'schema' => 'https', //协议头部，默认为 http
            'credentials' => [
                'secretId' => $cloudStorageSTS['credentials']['tmpSecretId'],
                'secretKey' => $cloudStorageSTS['credentials']['tmpSecretKey'],
                'token' => $cloudStorageSTS['credentials']['sessionToken']
            ]
        ];
        $this->cloudInstance = new Client($clientOption);
        return $this;
    }

    /**
     * 获取默认Bucket
     * @param null $bucket
     * @return mixed
     */
    private function getDefaultBucket($bucket = null)
    {
        return $bucket == null ? $this->cloudOption->getOption('COS_BUCKET') : $bucket;
    }
}
