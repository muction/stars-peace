<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>登录页面 - 后台管理系统</title>
    <link rel="icon" href="favicon.ico" type="image/ico">
    <link href="{{asset("static/stars/css/bootstrap.min.css")}}" rel="stylesheet">
    <link href="{{asset("static/stars/css/materialdesignicons.min.css")}}" rel="stylesheet">
    <link href="{{asset("static/stars/css/style.min.css")}}" rel="stylesheet">
    <link href="{{asset('static/stars/css/animate.css')}}" rel="stylesheet">

    <script type="text/javascript" src="{{asset('static/stars/js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/main.min.js')}}"></script>

    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap-notify.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/lightyear.js')}}"></script>

    <style>
        body{
            background: url("{{asset('static/stars/images/login_bg.jpg')}}") top center;
        }
        .lyear-wrapper {
            position: relative;
            /*background: #056EC8;*/
        }
        .lyear-login {
            display: flex !important;
            min-height: 100vh;
            align-items: center !important;
            float: right;
            margin-right: 50px;
            /*justify-content: right !important;*/
        }
        .login-center {
            background: #f7f7f7;
            min-width: 48.25rem;
            padding: 2.14286em 3.57143em;
            border-radius: 8px;
            margin: 2.85714em 0;
            box-shadow: 1px 1px 5px #333333;
        }
        .login-header {
            margin-bottom: 1.5rem !important;
        }
        .login-center .has-feedback.feedback-left .form-control {
            padding-left: 38px;
            padding-right: 12px;
        }
        .login-center .has-feedback.feedback-left .form-control-feedback {
            left: 0;
            right: auto;
            width: 38px;
            height: 38px;
            line-height: 38px;
            z-index: 4;
            color: #dcdcdc;
        }
        .login-center .has-feedback.feedback-left.row .form-control-feedback {
            left: 15px;
        }
        .login-center .login-input{
            height: 40px;
        }
    </style>
</head>

<body>
<div class="row lyear-wrapper">
    <div class="lyear-login">
        <div class="login-center">
            <div class="login-header text-center">
                <H4>后台管理系统</H4>
            </div>
            <form action="{{ route('rotate.auth.login.handle') }}" method="post">
                @csrf

                <div class="form-group has-feedback feedback-left error">
                    <input type="text" placeholder="请输入您的用户名" class="form-control login-input" name="username" id="username" value="{{ old('username') }}"/>
                    <span class="mdi mdi-account form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="form-group has-feedback feedback-left">
                    <input type="password" placeholder="请输入密码" class="form-control login-input" id="password" name="password" value="{{ old('password') }}" />
                    <span class="mdi mdi-lock form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="form-group has-feedback feedback-left row">
                    <div class="col-xs-8">
                        <input type="text" name="captcha" class="form-control login-input" placeholder="验证码" value="{{old('captcha')}}">
                        <span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
                    </div>
                    <div class="col-xs-4">
                        <img src="{{ captcha_src('math') }}" class="pull-right" id="captcha" style="cursor: pointer;height: 36px" onclick="this.src=this.src+'?d='+Math.random();" title="点击刷新" alt="captcha">
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-block btn-primary" type="submit" id="loginBtn">立即登录</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if ($errors->any())
    <script type="text/javascript">
        lightyear.notify( '登录失败，请检查用户名，密码，验证码是否输入正确！' , 'danger', 2000);
    </script>
@endif

<script type="text/javascript" src="{{asset("static/stars/js/jquery.min.js")}}"></script>
<script type="text/javascript" src="{{asset("static/stars/js/bootstrap.min.js")}}"></script>
</body>
</html>
