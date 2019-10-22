<?php
namespace Stars\Peace\Service;

use Stars\Peace\Foundation\ServiceService;

class CommonService extends ServiceService
{
    /**
     * 获取当前时间
     * @return false|string
     */
    public static function now(){
        return date('Y-m-d H:i:s');
    }
}