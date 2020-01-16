
@php( $_routeName = Request()->route()->getName() )
<ul id="myTabs" class="nav nav-tabs" role="tablist">
    <li @if( $_routeName == 'rotate.nav.index') class="active" @endif >
        <a href="{{ route('rotate.nav.index') }}" >导航列表</a>
    </li>

    <li @if( $_routeName == 'rotate.nav.add') class="active" @endif >
        <a href="{{ route('rotate.nav.add') }}" >新增导航</a>
    </li>


    @if( isset($nav['id']) )

        <li  @if( $_routeName == 'rotate.menu.index') class="active" @endif>
            <a href="{{ route('rotate.menu.index' ,['navId'=> $nav['id']]) }}" >{{ $nav['title'] }}菜单列表</a>
        </li>
        <li  @if( $_routeName == 'rotate.menu.add') class="active" @endif>
            <a href="{{ route('rotate.menu.add' ,['navId'=> $nav['id']]) }}" >新增菜单</a>
        </li>

        @if( isset($info) )
            <li  @if( $_routeName == 'rotate.menu.bind') class="active" @endif>
                <a href="{{ route('rotate.menu.bind' ,['navId'=> $nav['id'] ,'menuId'=>$info['id']]) }}" >绑定模型</a>
            </li>
        @endif

        @if( isset($bind)  && $bind &&  $_routeName == 'rotate.menu.bind.edit')
            <li class="active">
                <a href="{{ route('rotate.menu.bind' ,['navId'=> $nav['id'] ,'menuId'=>$info['id'] ,'bindId'=>$bind['id']]) }}" >
                    修改绑定
                </a>
            </li>
        @endif

        <li  @if( $_routeName == 'rotate.nav.menu.copy') class="active" @endif>
            <a href="{{ route( 'rotate.nav.menu.copy'  ,['navId'=> $nav['id']]) }}" >复制菜单</a>
        </li>
    @endif
</ul>
