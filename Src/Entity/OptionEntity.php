<?php
namespace Stars\Peace\Entity;

use Illuminate\Support\Facades\DB;
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
    public static function storage(array $storage ){
        try{
           DB::beginTransaction();

           //清空原配置
           self::where('id','>',0 )->delete();

           //写入新配置
           $insert= self::insert($storage);
           DB::commit();
           return $insert;
        }catch (\Exception $exception){
           DB::rollBack();
        }
    }


}
