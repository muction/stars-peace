<?php
/**
 * ------------------------------------------------------
 * 阿里云 - OSS
 * ------------------------------------------------------
 */

namespace Stars\Peace\Plugs\Cloud\Storage;

use OSS\OssClient;
use Stars\Peace\Plugs\Cloud\Storage\OSS\OssCloudOption;
use Stars\Peace\Plugs\Cloud\Storage\OSS\OssCloudStorage;
use Stars\Peace\Plugs\Cloud\Storage\OSS\OssCloudSTS;

class Oss
{
    private $ossAccessKeyId;
    private $ossAssessSecretKey;
    private $bucket;
    private $endpoint;
    private $region;
    private $allAction;
    private $allowPrefix;
    private $duration;
    private $option;
    private $sts;
    private $object;
    private $ossRoleArn;
    private $ossRoleSessionName;


    /**
     * 阿里云存储操作对象
     */
    public function __construct($ossAccessKeyId, $ossAssessSecretKey, $bucket, $endpoint, $region, $allowPrefix, $allAction, $duration, $ossRoleArn, $ossRoleSessionName)
    {
        $this->ossAccessKeyId = $ossAccessKeyId;
        $this->ossAssessSecretKey = $ossAssessSecretKey;
        $this->bucket = $bucket;
        $this->endpoint = $endpoint;
        $this->region = $region;
        $this->allAction = $allAction;
        $this->allowPrefix = $allowPrefix;
        $this->duration = $duration;
        $this->ossRoleArn = $ossRoleArn;
        $this->ossRoleSessionName = $ossRoleSessionName;

        $this->option = new OssCloudOption();
        $this->sts = new OssCloudSTS();
        $this->object = $this->instanceOss();
    }

    private function instanceOss()
    {
        $this->option->addOption('OSS_ACCESS_KEY_ID', $this->ossAccessKeyId);
        $this->option->addOption('OSS_ACCESS_SECRET', $this->ossAssessSecretKey);
        $this->option->addOption('OSS_ENDPOINT', $this->endpoint);
        $this->option->addOption('OSS_REGION', $this->region);
        $this->option->addOption('OSS_BUCKET', $this->bucket);
        $this->option->addOption('OSS_ROLE_ARN', $this->ossRoleArn);
        $this->option->addOption('OSS_ROLE_SESSION_NAME', $this->ossRoleSessionName);
        $this->option->addOption('OSS_ALLOW_ACTION', $this->allAction);
        $this->option->addOption('OSS_ALLOW_PREFIX', $this->allowPrefix);
        $oss = new OssCloudStorage($this->option);
        return $oss->cloudInstance($this->sts);
    }

    /**
     * 单文件上传
     * @param $localFile
     * @param $saveFileName
     * @param null $bucket
     * @return mixed|void
     */
    public function simpleUpload($localFile, $saveFileName, $bucket = null)
    {
        return $this->object->simpleUpload($localFile, $saveFileName, $bucket);
    }

    /**
     * 分片上传
     * @param $localFile
     * @param $saveFileName
     * @param null $bucket
     * @return false|mixed|void
     * @throws \OSS\Core\OssException
     */
    public function sliceUpload($localFile, $saveFileName, $bucket = null)
    {
        return $this->object->sliceUpload($localFile, $saveFileName, $bucket);
    }

    /**
     * 文件是否存在
     * @param $object
     * @param $bucket
     * @return mixed
     */
    public function hasObject($object, $bucket){
        return $this->object->hasObject($object, $bucket);
    }

    /**
     * 签名URL
     * @param $object
     * @param $bucket
     * @param $expiredTime
     * @return mixed
     */
    public function signUrl($object, $bucket, $expiredTime){
        return $this->object->signObject($object, $bucket, $expiredTime);
    }
}
