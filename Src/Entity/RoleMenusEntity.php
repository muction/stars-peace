<?php
namespace Stars\Peace\Entity;

use Stars\Peace\Foundation\EntityEntity;

class RoleMenusEntity extends EntityEntity
{
    protected $table = 'role_menus';
    protected $fillable = ['role_id' ,'menus_id'  ];

    /**
     * 取得角色的所有授权菜单
     * @param array $roleIds
     * @return mixed
     */
    public static function allMenuIds( array $roleIds ){
        return self::whereIn('role_id', $roleIds)->pluck('menu_id');
    }
}
