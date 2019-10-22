<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\NavEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;

class NavService extends ServiceService
{
    /**
     * save a nav
     * @param Request $request
     * @return mixed
     */
    public function storage( Request $request ){

        $only=['title' ,'remark' ,'article'];
        if($request->input('id')){
            return NavEntity::edit(
                $request->input('id') ,
                $request->only( $only ));
        }
        return NavEntity::storage( $request->only( $only ) );
    }

    /**
     * data list
     * @return mixed
     */
    public function pagination(){
        return NavEntity::paginatePage( 15 );
    }

    /**
     * remove a nav data
     * @param $navId
     * @return bool
     */
    public function remove( $navId ){

        return NavEntity::remove( $navId );
    }

    /**
     * info
     * @param $navId
     * @return mixed
     */
    public function info( $navId ){

        return NavEntity::info( $navId ) ;
    }

    /**
     * @return NavEntity[]|\Illuminate\Database\Eloquent\Collection
     */
    public function articleNav(){
        return NavEntity::articleNav();
    }
}