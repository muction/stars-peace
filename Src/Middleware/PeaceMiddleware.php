<?php

namespace Stars\Peace\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Stars\Peace\Entity\LogEntity;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function AlibabaCloud\Client\json;

class PeaceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //配置了IP白名单
        if( config('stars.common.allowIp' ) && !in_array($request->getClientIp() , config('stars.common.allowIp')) ){
            //throw new \Exception("禁止访问");
            throw new NotFoundHttpException();
        }

        //配置了IP黑名单
        if( config('stars.common.denyIp' ) && in_array($request->getClientIp() , config('stars.common.denyIp')) ){
            //throw new \Exception("禁止访问");
            throw new NotFoundHttpException();
        }

        //验证登录
        if( !Auth::check() ){
            return redirect( route('rotate.auth.login.page') );
        }

        //记录请求日志
        LogEntity::middleLog( $request->route()->getName(),  json_encode($request->all(), JSON_UNESCAPED_UNICODE) );

        return $next($request);
    }
}
