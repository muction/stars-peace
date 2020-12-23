<?php

namespace Stars\Peace\Plugs\Cloud\Storage\COS;

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
    private $cloudOption = null;

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
     */
    public function simpleUpload($localFile, $saveFile, $bucket=null)
    {
        $bucket = $bucket==null? $this->cloudOption->getOption('COS_BUCKET') : $bucket;
        if ($f = fopen($localFile, 'rb')) {
            $option=[
                'Bucket' => $bucket,
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
     */
    public function sliceUpload($localFile, $saveFile, $bucket, $size = 0.5)
    {
        // TODO: Implement sliceUpload() method.
    }

    /**
     * 判断是否有某个对象
     * @param $object
     * @param $bucket
     * @return mixed|void
     */
    public function hasObject($object, $bucket)
    {
        return $this->cloudInstance->doesObjectExist($bucket, $object);
    }


    /**
     * 签名一个object
     * @param $bucket
     * @param $object
     * @param $expiredTime
     * @return mixed|void
     */
    public function signObject($bucket, $object, $expiredTime)
    {
        return $this->cloudInstance->getObjectUrl($bucket, $object, '+' . $expiredTime . ' minutes');
    }

    /**
     * @param CloudStorageSTSInterface $cloudStorageSTS
     * @return $this|mixed
     */
    public function cloudInstance(CloudStorageSTSInterface $cloudStorageSTS)
    {
        $cloudStorageSTS= $cloudStorageSTS->makeUploadKey($this->cloudOption);
        $clientOption=[
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

}
