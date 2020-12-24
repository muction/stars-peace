<?php
namespace Stars\Peace\Contracts\Cloud\Storage;

/**
 * ------------------------------------------------------
 *  对象云存储 - 配置
 * ------------------------------------------------------
 */
interface CloudStorageOptionInterface
{
    /**
     * 增加一个配置项
     * @param $key
     * @param $value
     * @return mixed
     */
    public function addOption($key, $value);

    /**
     * 获取一个配置项
     * @param $key
     * @return mixed
     */
    public function getOption($key);

}
