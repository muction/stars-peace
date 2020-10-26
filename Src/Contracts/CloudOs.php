<?php

namespace Stars\Peace\Contracts;

/**
 * 云存储接口
 *
 */
interface CloudOs
{
    /**
     * 临时授权秘钥
     * @return mixed
     */
    public function tempAuthToken(array $options);

    /**
     * 简单上传
     * @return mixed
     */
    public function simpleUpload(array $options);

    /**
     * 切片上传
     * @param array $options
     * @return mixed
     */
    public function sliceUpload(array $options);

    /**
     * 临时预览地址
     * @param array $options
     * @return mixed
     */
    public function tempPreviewUrl(array $options);

    /**
     * 是否存在某个对象
     * @param array $options
     * @return mixed
     */
    public function hasObject(array $options);

    /**
     * 是否存在某个桶
     * @param array $options
     * @return mixed
     */
    public function hasBucket(array $options);
}
