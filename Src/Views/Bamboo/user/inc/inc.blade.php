
@php( $_routeName = Request()->route()->getName() )
@php( $_isFromProfile = Request() ->get('f') )
<ul id="myTabs" class="nav nav-tabs" role="tablist">

    @if( !$_isFromProfile )
    <li @if( $_routeName == 'rotate.user.index') class="active" @endif >
        <a href="{{ route('rotate.user.index') }}" >账户列表</a>
    </li>
    <li  @if( $_routeName == 'rotate.user.addPage') class="active" @endif>
        <a href="{{ route('rotate.user.addPage') }}" >新增账户</a>
    </li>
    @endif

    @if( isset($info['id']) )
        @if( isset($isEditProfile ))
            <li  @if( $_routeName ==  'rotate.user.editProfilePage' ) class="active" @endif>
                <a href="{{ route(  'rotate.user.editProfilePage' , ['infoId'=>$info['id'] ,'f'=> $_isFromProfile ]) }}" >修改信息</a>
            </li>
        @else
            <li  @if( $_routeName == 'rotate.user.editPage') class="active" @endif>
                <a href="{{ route('rotate.user.editPage' , ['infoId'=>$info['id'] ]) }}" >编辑账户</a>
            </li>
        @endif
    @endif
</ul>