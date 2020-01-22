<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\SlideTypeEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class SlideTypeService extends ServiceService
{
    /**
     * @param Request $request
     * @param int $infoId
     * @return bool|mixed
     */
    public function storageType(Request $request, $infoId =0 ){
        $storage = $request->only(['title' ,'order']);
        if($infoId){
            return SlideTypeEntity::edit( $storage, $infoId );
        }
        return SlideTypeEntity::storage( $storage );
    }

    /**
     * @return SlideTypeEntity[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(){

        return SlideTypeEntity::all();
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function remove( $infoId ){
        return SlideTypeEntity::remove( $infoId  );
    }

    /**
     * @param $infoId
     * @return mixed
     */
    public function info( $infoId ){
        return SlideTypeEntity::info($infoId );
    }

    /**
     * all type
     * @return SlideTypeEntity[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allTypePermissions(){
        return SlideTypeEntity::allType();
    }


}