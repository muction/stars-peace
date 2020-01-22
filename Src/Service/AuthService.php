<?php
namespace Stars\Peace\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Http\Request;
use Stars\Rbac\Entity\UserEntity;

class AuthService extends ServiceService
{
    public function login( $username, $password ){

        return Auth::attempt(['username'=>$username, 'password'=>$password]);
    }
}
