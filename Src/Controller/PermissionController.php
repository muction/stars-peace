<?php

namespace Stars\Peace\Controller;

use Stars\Peace\Service\PermissionService;
use Stars\Peace\Service\PermissionTypeService;
use Stars\Peace\Service\RoleService;
use Illuminate\Http\Request;

class PermissionController extends PeaceController
{

    /**
     * 权限列表
     */
    public function index(PermissionService $permissionService){

        $datas= $permissionService->all();
        return $this->view( "permission.index" , ['datas'=>$datas ]);
    }

    /**
     * 添加
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addPage(PermissionTypeService $permissionTypeService ){
        $types = $permissionTypeService->all();
        return $this->view( "permission.form" , ['types'=>$types ] );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addTypePage(){

        return $this->view( "permission.type.form" );
    }

    /**
     * 增加类别
     */
    public function addTypePageStorage(Request $request , PermissionTypeService $permissionTypeService, $infoId=0 ){

        $this->validate( $request , ['title'=>'required' ,'order'=>'required'] );
        $withError=['messageError'=> '保存失败'];
        if( $permissionTypeService->storageType( $request , $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.permission.types' ) )->withErrors( $withError) ;

    }

    /**
     * @param PermissionTypeService $permissionTypeService
     * @param int $infoId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editTypePage( PermissionTypeService $permissionTypeService, $infoId=0 ){

        $info = $permissionTypeService->info($infoId );
        return $this->view( "permission.type.form"  , ['permissionType'=> $info ] );
    }

    /**
     * @param PermissionTypeService $permissionTypeService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function type(PermissionTypeService $permissionTypeService){

        $datas = $permissionTypeService->all();
        return $this->view( "permission.type.index" , ['datas'=>$datas ]);
    }

    /**
     * @param PermissionTypeService $permissionTypeService
     * @param $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function removeType(PermissionTypeService $permissionTypeService , $infoId ){

        $withError=['messageError'=> '保存失败'];
        if( $permissionTypeService->remove( $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.permission.types' ) )->withErrors( $withError) ;
    }

    /**
     * 编辑
     * @param PermissionService $permissionService
     * @param $infoId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPage(PermissionService $permissionService,PermissionTypeService $permissionTypeService, $infoId ){
        $info = $permissionService->info( $infoId );
        $types = $permissionTypeService->all();

        return $this->view( "permission.form" , ['info'=>$info  ,'types'=>$types] );

    }

    /**
     * @param Request $request
     * @param PermissionService $permissionService
     * @param int $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storage(Request $request, PermissionService $permissionService , $infoId =0){
        $validatorRule =[
            'title'=>'required|unique:permissions,title,'.$infoId ,
            'type'=>"required" ,
            'display_name'=>"required" ,
            'description' => 'required' ,
            'status'=>'required'
        ] ;

        $this->validate( $request , $validatorRule );
        $result = $permissionService->storage( $request , $infoId );
        $withError=['messageError'=> '保存失败'];
        if( $result ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.permission.index') )->withErrors( $withError) ;
    }

    /**
     * 删除
     * @param PermissionService $permissionService
     * @param $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove(PermissionService $permissionService, $infoId ){

        $withError=['messageError'=> '保存失败'];
        if( $permissionService->remove( $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.permission.index') )->withErrors( $withError ) ;
    }

}
