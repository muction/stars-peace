<?php
namespace Stars\Peace\Plugs\Cloud\Storage;

use Stars\Peace\Plugs\Cloud\Storage\COS\CosCloudStorage;

class CloudStoragePlug
{
    private static $cosInstance=null;
    private static $ossInstance=null;


//    /**
//     * 腾讯云存储操作对象
//     */
//    public static function cosInstance(){
//        if(self::$cosInstance == null){
//            self::$cosInstance = new CosCloudStorage();
//        }
//        return self::$cosInstance;
//    }
}
