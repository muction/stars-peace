<?php


namespace Stars\Peace\Foundation;


use Stars\Peace\Contracts\Service;

class ServiceService implements Service
{

    /**
     * 对象
     * @var array
     */
    private static $instance=[];

    /**
     * 获取对象
     * @return ServiceService|null
     */
    public static function instance()
    {
        $classKey =self::getInstanceKey();
        if(! isset(self::$instance[ $classKey ]) ){
            self::$instance[$classKey] = new static() ;
        }
        return self::$instance[$classKey];
    }

    /**
     * 禁止克隆
     */
    public function __clone()
    {
        die('Clone is not allow '.E_USER_ERROR );
    }

    /**
     * 取得key
     * @return string
     */
    private static function getInstanceKey(){
        return md5(static::class);
    }

}