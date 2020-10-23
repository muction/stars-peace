<?php

namespace Stars\Peace\Contracts;
interface CloudStorage
{
    /**
     * 获取临时上传秘钥
     * @return mixed
     */
    public function getTempUploadKey();

    /**
     * 对象是否存在
     * @param $objectName
     * @return mixed
     */
    public function hasObject($objectName);


    /**
     * 获取Object临时授权地址
     * @param $objectName
     * @return mixed
     */
    public function getPreviewUrl($objectName);

    /**
     * 格式化响应结果
     * @param $sessionToken
     * @param $tmpSecretId
     * @param $tmpSecretKey
     * @param $startTime
     * @param $expiredTime
     * @return mixed
     */
    public function responseStsResult($sessionToken, $tmpSecretId, $tmpSecretKey, $startTime, $expiredTime );
}
