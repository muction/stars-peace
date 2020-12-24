<?php
namespace Stars\Peace\Contracts\Cloud\Storage;

/**
 * ------------------------------------------------------
 *  对象云存储 - 句柄操作
 * ------------------------------------------------------
 */
interface CloudStorageInterface
{
    /**
     * 获取操作对象
     * @return mixed
     */
    public function cloudInstance(CloudStorageSTSInterface $cloudStorageSTS);

}
