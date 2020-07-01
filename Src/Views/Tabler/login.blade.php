<form action="{{ route('rotate.auth.login.handle') }}" method="post">
            @csrf

            <div class="form-group has-feedback feedback-left error">
                <input type="text" placeholder="请输入您的用户名" class="form-control" name="username" id="username" value="{{ old('username') }}"/>
                <span class="mdi mdi-account form-control-feedback" aria-hidden="true"></span>
            </div>
            <div class="form-group has-feedback feedback-left">
                <input type="password" placeholder="请输入密码" class="form-control" id="password" name="password" value="{{ old('password') }}" />
                <span class="mdi mdi-lock form-control-feedback" aria-hidden="true"></span>
            </div>
            <div class="form-group has-feedback feedback-left row">
                <div class="col-xs-7">
                    <input type="text" name="captcha" class="form-control" placeholder="验证码" value="{{old('captcha')}}">
                    <span class="mdi mdi-check-all form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class="col-xs-5">
                    <img src="{{ captcha_src('math') }}" class="pull-right" id="captcha" style="cursor: pointer;width: 120px;height: 36px" onclick="this.src=this.src+'?d='+Math.random();" title="点击刷新" alt="captcha">
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-block btn-primary" type="submit" onclick="location.href='index.html'">立即登录

                </button>
            </div>
        </form>
@if ($errors->any())
<script type="text/javascript">
    lightyear.notify( '登录失败~' , 'danger', 2000);
</script>
@endif


