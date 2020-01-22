<?php
namespace Stars\Peace\Service;

use Stars\Rbac\Entity\PermissionTypeEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class PermissionTypeService extends ServiceService
{
    /**
     * @param Request $request
     * @param int $infoId
     * @return bool|mixed
     */
    public function storageType(Request $request, $infoId =0 ){
        $storage = $request->only(['title' ,'order']);
        if($infoId){
            return PermissionTypeEntity::edit( $storage, $infoId );
        }
        return PermissionTypeEntity::storage( $storage );
    }

    /**
     * @return PermissionTypeEntity[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(){

        return PermissionTypeEntity::all();
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function remove( $infoId ){
        return PermissionTypeEntity::remove( $infoId  );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function info( $infoId ){
        return PermissionTypeEntity::info($infoId );
    }

    /**
     * all type
     * @return PermissionTypeEntity[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allTypePermissions(){
        return PermissionTypeEntity::allType();
    }


}