<?php
namespace Stars\Peace\Plugs\Cloud\Storage;

use Stars\Peace\Plugs\Cloud\Storage\COS\CosCloudOption;
use Stars\Peace\Plugs\Cloud\Storage\COS\CosCloudStorage;
use Stars\Peace\Plugs\Cloud\Storage\COS\CosCloudSTS;

class CosStorage
{
    private  $cosSecretId;
    private  $cosSecretKey;
    private  $bucket;
    private  $region;
    private  $allAction;
    private  $allowPrefix;
    private  $duration;
    private  $option;
    private  $sts;


    /**
     * 腾讯云存储操作对象
     */
    public function __construct($cosSecretId, $cosSecretKey, $bucket, $region, $allAction, $allowPrefix, $duration){
        $this->cosSecretId = $cosSecretId;
        $this->cosSecretKey = $cosSecretKey;
        $this->bucket = $bucket;
        $this->region = $region;
        $this->allAction = $allAction;
        $this->allowPrefix = $allowPrefix;
        $this->duration = $duration;
        $this->option = new CosCloudOption();
        $this->sts = new CosCloudSTS();
    }

    /**
     * 初始化操作对象
     * @param $cosSecretId
     * @param $cosSecretKey
     * @param $bucket
     * @param $region
     * @param $allAction
     * @param $allowPrefix
     * @return mixed|CosCloudStorage
     */
    public function instanceCos(){

        $this->option->addOption('COS_SECRET_ID',  $this->cosSecretId);
        $this->option->addOption('COS_SECRET_KEY',  $this->cosSecretKey);
        $this->option->addOption('COS_BUCKET',  $this->bucket);
        $this->option->addOption('COS_REGION',  $this->region);
        $this->option->addOption('COS_ALLOW_PREFIX',  $this->allowPrefix);
        $this->option->addOption('COS_DURATION_SECONDS',  $this->cosSecretId);
        $this->option->addOption('COS_ALLOW_ACTION',   $this->allAction );

        $cos = new CosCloudStorage($this->option);
        return $cos->cloudInstance($this->sts);
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function addOption($key, $value){
        return $this->option->addOption($key, $value);
    }

}
