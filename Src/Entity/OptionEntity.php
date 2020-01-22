<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class OptionEntity extends EntityEntity
{
    protected $table = 'options';

    protected $fillable = ['type' ,'key' ,'value'];

    /**
     * @param array $storage
     * @param $type
     * @return array|bool
     */
    public static function storage(array $storage , $type ){
        if (!$storage) return [];

        foreach ($storage as $key=>$value){
            self::updateOrCreate(['key'=>$key] ,['value'=>$value ,'type' => $type ]);
        }

        return true;
    }


}