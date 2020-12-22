<?php

namespace Stars\Peace\Plugs\Cloud\Storage\COS;

use Stars\Peace\Plugs\Cloud\Storage\CloudStorageInterface;
use \Qcloud\Cos\Client;

class CosCloudStorage implements CloudStorageInterface
{
    private $options = [];
    private $instance = null;

    /**
     * 获取实例
     */
    public function getClient()
    {
        $this->instance = new Client(
            [
                'region' => $this->options['COS_REGION'],
                'schema' => 'https', //协议头部，默认为http
                'credentials' => array(
                    'secretId' => $this->options['COS_SECRETID'],
                    'secretKey' => $this->options['COS_SECRETKEY']
                )
            ]
        );
    }

    /**
     * 设置客户端
     */
    public function setClient($cosSecretId, $secretKey, $region)
    {
        $this->addOption('COS_SECRETID', $cosSecretId);
        $this->addOption('COS_SECRETKEY', $secretKey);
        $this->addOption('COS_REGION', $region);
        return $this;
    }


    /**
     * 增加配置
     */
    public function addOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    public function hasObject($object)
    {
        // TODO: Implement hasObject() method.
    }

    public function putObject($args = [])
    {
        // TODO: Implement putObject() method.
    }
}
