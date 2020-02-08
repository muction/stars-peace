<?php

namespace Stars\Peace\Controller;

use Illuminate\Support\Facades\Hash;
use Stars\Peace\Service\RoleService;
use Stars\Peace\Service\UserService;
use Illuminate\Http\Request;
use Stars\Rbac\Entity\UserEntity;

/**
 * 后台账户
 * Class UserController
 * @package Stars\Peace\Controller
 */
class UserController extends PeaceController
{
    /**
     * @param UserService $userService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index( UserService $userService){
        $datas=$userService->paginatePage();
        return $this->view( "user.index", ['datas'=>$datas] );
    }

    /**
     * @param UserService $userService
     * @param RoleService $roleService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addPage( UserService $userService, RoleService $roleService ){

        $roles = $roleService->index();
        return $this->view( "user.form" , ['roles'=> $roles] );
    }

    /**
     * 编辑页面
     * @param UserService $userService
     * @param RoleService $roleService
     * @param $infoId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPage(UserService $userService,  RoleService $roleService, $infoId ){
        $roles = $roleService->index();
        $info = $userService->info( $infoId );

        return $this->view("user.form", ['info'=>$info ,'hasRoles'=> $info ? array_column( ($info->toArray())['roles'] , 'id' ) : [] , 'roles'=> $roles ] );
    }

    /**
     * @param UserService $userService
     * @param $infoId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editProfile(UserService $userService, $infoId){
        $info = $userService->info( $infoId );
        return $this->view("user.form", ['info'=>$info ,'isEditProfile'=> true  ] );
    }

    /**
     * 删除
     * @param UserService $userService
     * @param $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove(UserService $userService, $infoId){
        $withError=['messageError'=> '保存失败'];
        if( $userService->remove( $infoId )){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.user.index') )->withErrors(
            $withError
        ) ;
    }

    public function storage(Request $request, UserService $userService , $infoId =0){
        $isEditProfile = $request->get('isEditProfile') ;
        $validatorRule =[
            'username'=>'required|unique:users,username,'.$infoId ,
            'password' => 'required|confirmed',
            'password_confirmation' => 'required|same:password'
        ] ;
        if( $infoId ){


            if($isEditProfile){
                $validatorRule=[
                    'password' => 'confirmed',
                    'password_confirmation' => 'same:password',
                    'origin_password'=>  'required'
                ];
            }else{
                $validatorRule =[
                    'username'=>'required|unique:users,username,'.$infoId ,
                    'password' => 'confirmed',
                    'password_confirmation' => 'same:password',
                ] ;
            }

        }
        $this->validate( $request , $validatorRule );
        $withError=['messageError'=> '保存失败'];
        if($isEditProfile){
            $info = UserEntity::info( $infoId );
            if( !Hash::check( $request->post('origin_password')  , $info->password) ){
                $withError = ['messageError'=> '原始密码错误~'] ;
                return redirect( route('rotate.user.editProfilePage' , ['infoId'=>$infoId ,'f'=>$isEditProfile]) )->withErrors(
                    $withError
                ) ;
            }
        }

        $result = $userService->storage( $request , $infoId ,$isEditProfile );

        if( $result ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }

        $redirectRouteName = $isEditProfile? 'rotate.user.editProfilePage'  : 'rotate.user.index';
        return redirect( route($redirectRouteName , ['infoId'=>$infoId ,'f'=>$isEditProfile]) )->withErrors(
            $withError
        ) ;
    }
}
