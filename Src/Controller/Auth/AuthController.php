<?php
namespace Stars\Peace\Controller\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stars\Peace\Controller\PeaceController;
use Stars\Peace\Service\AuthService;
use Stars\Rbac\Entity\UserEntity;


class AuthController extends PeaceController
{
    //登录页
    public function login(){

        return $this->view('auth.login');
    }

    /**
     * 登录系统
     * @param Request $request
     * @param AuthService $authService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginHandle(Request $request, AuthService $authService){
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ],[
            'captcha.required' => trans('validation.required'),
            'captcha.captcha' => trans('validation.captcha'),
        ]);

       $result = $authService->login(
           $request->post('username') ,
           $request->post('password')
       );

       if(!$result){
           return redirect( route('rotate.auth.login.page') )->withErrors( ['errorMessage'=>'账号密码错误']);
       }

       return redirect( route('rotate.home.index') );
    }

    /**
     * 退出系统
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout(){

        Auth::logout();
        return redirect( route('rotate.auth.login.page') );
    }

}
