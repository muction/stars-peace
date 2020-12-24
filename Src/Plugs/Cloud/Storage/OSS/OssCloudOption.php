<?php
/**
 * ------------------------------------------------------
 *
 * ------------------------------------------------------
 */
namespace Stars\Peace\Plugs\Cloud\Storage\OSS;
use Stars\Peace\Contracts\Cloud\Storage\CloudStorageOptionInterface;

class OssCloudOption implements CloudStorageOptionInterface
{
    private $option=[];

    public function addOption($key, $value)
    {
        $this->option[$key]=$value;
    }

    public function getOption($key)
    {
        return isset($this->option[$key]) ? $this->option[$key] : null;
    }
}
