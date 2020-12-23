<?php
/**
 * ------------------------------------------------------
 *
 * ------------------------------------------------------
 */

namespace Stars\Peace\Plugs\Cloud\Storage\COS;
use QCloud\COSSTS\Sts;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageOptionInterface;
use Stars\Peace\Plugs\Cloud\Storage\CloudStorageSTSInterface;

class CosCloudOption implements CloudStorageOptionInterface
{
    /**
     * 配置项
     * @var array
     */
    private $option=[];

    /**
     * 增加一个配置项
     * @param $key
     * @param $value
     * @return $this|mixed
     */
    public function addOption($key, $value)
    {
        $this->option[$key] = $value;
        return $this;
    }

    /**
     * 获取一个配置项，无时返回null
     * @param $key
     * @return mixed|null
     */
    public function getOption($key)
    {
        return isset($this->option[$key]) ? $this->option[$key] : null;
    }
}
