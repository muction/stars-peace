<?php


namespace Stars\Peace\Plugs\Cloud\Storage;


interface CloudStorageInterface
{
    /**
     * 获取操作对象
     * @return mixed
     */
    public function getClient();

    /**
     * 增加配置
     * @param $key
     * @param $value
     * @return mixed
     */
    public function addOption($key, $value);

    /**
     * 对象是否存在
     * @param $object
     * @return bool
     */
    public function hasObject($object);

    /**
     * 简单上传
     * @param array $args
     * @return mixed
     */
    public function putObject($args=[]);
}
