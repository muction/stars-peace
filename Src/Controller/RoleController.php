<?php

namespace Stars\Peace\Controller;

use Stars\Peace\Service\PermissionService;
use Stars\Peace\Service\PermissionTypeService;
use Stars\Peace\Service\RoleService;
use Illuminate\Http\Request;

class RoleController extends PeaceController
{

    /**
     * 列表页面
     * @param RoleService $roleService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(RoleService $roleService){
        $roles = $roleService->index();
        return $this->view( "role.index" , ['datas'=>$roles] );
    }

    /**
     * 增加页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addPage(){

        return $this->view("role.form");
    }

    /**
     * @param RoleService $roleService
     * @param $infoId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPage(RoleService $roleService , $infoId ){

        $info = $roleService->info( $infoId );
        return $this->view("role.form" , ['info'=>$info ]);

    }

    /**
     * @param Request $request
     * @param RoleService $roleService
     * @param int $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storage(Request $request , RoleService $roleService , $infoId=0 ){

        $this->validate($request ,
            [
                'title'=>'required|unique:roles,title,'.$infoId ,
                'display_name'=>'required' ,
                'description'=>'required' ,
                'status'=>'required'
            ]
        );

        $withError=['messageError'=> '保存失败'];
        if( $roleService->storage( $request, $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.role.index') )->withErrors(
            $withError
        ) ;
    }

    /**
     * 删除
     * @param RoleService $roleService
     * @param $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove( RoleService $roleService , $infoId ){
        $withError=['messageError'=> '保存失败'];
        if( $roleService->remove( $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.role.index') )->withErrors(
            $withError
        ) ;
    }


    /**
     * @param PermissionService $permissionService
     * @param PermissionTypeService $permissionTypeService
     * @param RoleService $roleService
     * @param $roleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bindRolePage( PermissionService $permissionService, PermissionTypeService $permissionTypeService, RoleService $roleService, $roleId ){

        $role = $roleService->info( $roleId );
        $permissions = $permissionService->all();
        $allTypePermissions = $permissionTypeService->allTypePermissions();
        return $this->view("role.bind" , [ 'role'=>$role , 'permissions'=>$permissions ,'allTypePermissions'=>$allTypePermissions ]);
    }

    /**
     * @param Request $request
     * @param PermissionService $permissionService
     * @param RoleService $roleService
     * @param $roleId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function bindRoleStorage(Request $request, PermissionService $permissionService, RoleService $roleService, $roleId ){

        $result=  $roleService->bindPermission( $request, $roleId );
        $withError=['messageError'=> '保存失败'];
        if( $result){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.role.index') )->withErrors(
            $withError
        ) ;
    }
}
