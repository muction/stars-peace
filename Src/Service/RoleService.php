<?php
namespace Stars\Peace\Service;

use Stars\Rbac\Entity\RoleEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class RoleService extends ServiceService
{
    /**
     * @return mixed
     */
    public function index(){

        return RoleEntity::index();

    }

    /**
     * 保存或更新
     * @param Request $request
     * @param int $infoId
     * @return mixed
     */
    public function storage(Request $request , $infoId=0 )
    {
        if($infoId){
            return RoleEntity::edit( $request->all() , $infoId );
        }
        return RoleEntity::storage( $request->all() );
    }

    /**
     * 删除
     * @param $infoId
     * @return bool
     */
    public function remove( $infoId ){

        return RoleEntity::remove( $infoId );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function info( $infoId){
        $roleInfo= RoleEntity::info( $infoId );
        $roleInfo = $roleInfo? $roleInfo->toArray() : [];
        if($roleInfo){
            $roleInfo['permissions'] = array_column($roleInfo['permissions']  ,'id'  );
        }

        return $roleInfo;
    }

    /**
     * 角色ID
     * @param Request $request
     * @param $roleId
     * @return bool|mixed
     */
    public function bindPermission( Request $request , $roleId ){

        return RoleEntity::bindPermission( $roleId , $request->input('permissions') );
    }
}