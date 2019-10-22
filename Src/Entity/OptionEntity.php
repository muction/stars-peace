<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class OptionEntity extends EntityEntity
{
    protected $table = 'options';

    /**
     * @param array $storage
     * @return array|bool
     */
    public static function storage(array $storage ){
        if (!$storage) return [];

        foreach ($storage as $key=>$value){
            self::updateOrCreate([$key=>$value]);
        }

        return true;
    }
}