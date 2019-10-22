<?php
namespace Stars\Peace\Service;

use Stars\Rbac\Entity\PermissionEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class PermissionService extends ServiceService
{
    /**
     * @param Request $request
     * @param int $infoId
     * @return mixed
     */
    public function storage(Request $request, $infoId=0){
        if($infoId){
            return PermissionEntity::edit( $request->all() , $infoId );
        }
        return PermissionEntity::storage( $request->all() );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function info( $infoId ){
        return PermissionEntity::info( $infoId );
    }

    /**
     * @param $infoId
     * @return bool
     */
    public function remove( $infoId ){

        return PermissionEntity::remove($infoId );
    }


    /**
     * @return PermissionEntity[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(){

        return PermissionEntity::all();
    }

    /**
     * @return mixed
     */
    public function paginatePage(){
        return PermissionEntity::paginatePage(  );
    }

}