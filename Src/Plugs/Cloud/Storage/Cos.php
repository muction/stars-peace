<?php

namespace Stars\Peace\Plugs\Cloud\Storage;

use Stars\Peace\Plugs\Cloud\Storage\COS\CosCloudOption;
use Stars\Peace\Plugs\Cloud\Storage\COS\CosCloudStorage;
use Stars\Peace\Plugs\Cloud\Storage\COS\CosCloudSTS;

class Cos
{
    private $cosSecretId;
    private $cosSecretKey;
    private $bucket;
    private $region;
    private $allAction;
    private $allowPrefix;
    private $duration;
    private $option;
    private $sts;
    private $object;


    /**
     * 腾讯云存储操作对象
     */
    public function __construct($cosSecretId, $cosSecretKey, $bucket, $region, $allowPrefix, $allAction, $duration)
    {
        $this->cosSecretId = $cosSecretId;
        $this->cosSecretKey = $cosSecretKey;
        $this->bucket = $bucket;
        $this->region = $region;
        $this->allAction = $allAction;
        $this->allowPrefix = $allowPrefix;
        $this->duration = $duration;
        $this->option = new CosCloudOption();
        $this->sts = new CosCloudSTS();
        $this->object = $this->instanceCos();
    }

    /**
     * 初始化操作对象
     * @param $cosSecretId
     * @param $cosSecretKey
     * @param $bucket
     * @param $region
     * @param $allAction
     * @param $allowPrefix
     * @return Cos
     */
    private function instanceCos()
    {

        $this->option->addOption('COS_SECRET_ID', $this->cosSecretId);
        $this->option->addOption('COS_SECRET_KEY', $this->cosSecretKey);
        $this->option->addOption('COS_BUCKET', $this->bucket);
        $this->option->addOption('COS_REGION', $this->region);
        $this->option->addOption('COS_ALLOW_PREFIX', $this->allowPrefix);
        $this->option->addOption('COS_DURATION_SECONDS', $this->duration);
        $this->option->addOption('COS_ALLOW_ACTION', $this->allAction);

        $cos = new CosCloudStorage($this->option);
        return $cos->cloudInstance($this->sts);
    }

    /**
     * 上传本地文件
     * @param $localFile
     * @param $saveFileName
     * @param null $bucket
     * @return false|mixed|void
     */
    public function simpleUpload($localFile, $saveFileName, $bucket=null){
        return $this->object->simpleUpload($localFile, $saveFileName, $bucket);
    }

    /**
     * 对象是否存在
     * @param $object
     * @param null $bucket
     * @return mixed
     */
    public function hasObject($object, $bucket=null){
        return $this->object->hasObject($object, $bucket);
    }

    /**
     * 签名对象
     * @param $object
     * @param null $bucket
     * @param int $durationTime
     * @return mixed
     */
    public function signObject( $object, $durationTime=3600, $bucket =null){
        return $this->object->signObject($object, $durationTime,  $bucket);
    }

    /**
     * 分片上传
     * @param $localFile
     * @param $saveFile
     * @param null $bucket
     * @param float $size
     * @return mixed
     */
    public function sliceUpload($localFile, $saveFile, $bucket=null, $size = 1000000){
        return $this->object->sliceUpload($localFile, $saveFile, $bucket, $size);
    }
}
