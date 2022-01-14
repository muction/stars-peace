<?php
namespace Stars\Peace\Controller\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Stars\Peace\Controller\PeaceController;
use Stars\Peace\Service\AuthService;
use Stars\Rbac\Entity\UserEntity;


class AuthController extends PeaceController
{
    //登录页
    public function login(){

        return $this->view('login');
    }

    /**
     * 登录系统
     * @param Request $request
     * @param AuthService $authService
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function loginHandle(Request $request, AuthService $authService){
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ] ,['validation.captcha' =>'验证码错误']);

       $result = $authService->login(
           $request->post('username') ,
           $request->post('password')
       );

       return $result ? redirect( route('rotate.home.index') ) : back()->withErrors(['error'=>'登录失败']);
    }

    /**
     * 退出系统
     * @return RedirectResponse|Redirector
     */
    public function logout(){

        Auth::logout();
        return redirect( route('rotate.auth.login.page') );
    }

}
