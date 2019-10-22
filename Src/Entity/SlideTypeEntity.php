<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class SlideTypeEntity extends EntityEntity
{
    protected $table = 'slide_types';

    protected $fillable = ['title' ,'order'];

    protected $hidden = ['created_at' ,'updated_at'];

    /**
     * 存储类型
     * @param array $storage
     * @return mixed
     */
    public static function storage(array $storage ){

        return self::create( $storage );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public static function remove( $infoId){
        return self::where('id', $infoId )->delete();

    }

    /**
     *
     * @param $infoId
     * @return mixed
     */
    public static function info( $infoId ){
        return self::find( $infoId) ;
    }

    /**
     * 编辑
     * @param $storage
     * @param $infoId
     * @return bool
     */
    public static function edit( $storage , $infoId ){

        $info = self::info($infoId );
        if(!$info){
            return false;
        }

        return $info->update( $storage );
    }

    /**
     * @return PermissionTypeEntity[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function allType(){
        return self::get();
    }


}