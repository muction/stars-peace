<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class MenuBindEntity extends EntityEntity
{
    protected $table= 'menu_binds';
    protected $fillable = [ 'title' ,'menu_id' ,'sheet_name' ,'alias_name' ,'options' ,'order' ,'status' ,'table_name' ];
    /**
     * @param array $allBinds
     * @return mixed
     */
    public static function saveAll(array $allBinds){
        return self::insert( $allBinds );
    }

    /**
     * 删除绑定
     * @param $menuId
     * @param $bindId
     * @return mixed
     */
    public static function remove( $menuId, $bindId ){

        return self::where( 'id', $bindId )
            ->where( 'menu_id' ,$menuId )
            ->delete();
    }

    /**
     * @param $menuId
     * @param $bindId
     * @return mixed
     */
    public static function info( $menuId, $bindId ){
        return self::where( 'id', $bindId )
            ->where( 'menu_id' ,$menuId )
            ->first();
    }

    /**
     * 绑定信息
     * @param $menuId
     * @return mixed
     */
    public static function bindAllInfo(  $menuId ){
        return self::where( 'menu_id' ,$menuId )
            ->orderBy('order' , 'desc')
            ->orderBy('id' , 'asc')
            ->get();
    }

    /**
     * 绑定详情
     * @param int $bindId
     * @return mixed
     */
    public static function bindDetail(int  $bindId ){
        return self::where( 'id', $bindId )
            ->first();
    }
}
