<li class="dropdown dropdown-profile">
    <a href="javascript:void(0)" data-toggle="dropdown">
        <span>
            {{ \Illuminate\Support\Facades\Auth::user()->username }}
            <span class="caret"></span>
        </span>
    </a>
    <ul class="dropdown-menu dropdown-menu-right">
        <li> <a class="multitabs" href="{{ route( 'rotate.user.editProfilePage' , ['infoId'=>\Illuminate\Support\Facades\Auth::id() ,'f'=>'profile' ]) }}" target="request-content">
                <i  class="mdi mdi-lock-outline"></i> 修改密码
            </a>
        </li>
        <li class="divider"></li>
        <li><a href="{{ route('rotate.auth.logout') }}"><i class="mdi mdi-logout-variant"></i> 退出登录</a>
        </li>
    </ul>
</li>
