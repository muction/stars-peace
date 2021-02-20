<?php
namespace Stars\Peace\Contracts\Cloud\Storage;

/**
 * ------------------------------------------------------
 *  对象云存储 - 对象操作
 * ------------------------------------------------------
 */
interface CloudStorageObject
{
    /**
     * 传入配置项
     * CloudStorageObject constructor.
     * @param CloudStorageOptionInterface $cloudStorageOption
     */
    public function __construct(CloudStorageOptionInterface $cloudStorageOption);

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
