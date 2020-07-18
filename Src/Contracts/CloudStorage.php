<?php

namespace Stars\Peace\Contracts;
interface CloudStorage
{
    /**
     * 获取临时上传秘钥
     * @return mixed
     */
    public function getTempUploadKey();
}
