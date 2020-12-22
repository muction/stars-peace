<?php
namespace Stars\Peace\Plugs\Cloud\Storage;

/**
 * ------------------------------------------------------
 *  对象云存储 - 对象操作
 * ------------------------------------------------------
 */
interface CloudStorageObject
{
    /**
     * 单文件上传
     * @param $localFile
     * @param $saveFile
     * @param $bucket
     * @return mixed
     */
    public function simpleUpload($localFile, $saveFile, $bucket);

    /**
     * 切片上传
     * @param $localFile
     * @param $saveFile
     * @param $bucket
     * @param float $size
     * @return mixed
     */
    public function sliceUpload($localFile, $saveFile, $bucket, $size=0.5);

    /**
     * 移除一个对象
     * @param $object
     * @param $bucket
     * @return mixed
     */
    public function removeObject($object, $bucket);

    /**
     * 是否存在一个对象
     * @param $object
     * @param $bucket
     * @return mixed
     */
    public function hasObject($object, $bucket);

    /**
     * 签名文件对象
     * @param $bucket
     * @param $object
     * @param $expiredTime
     * @return mixed
     */
    public function signObject($bucket, $object, $expiredTime);
}
