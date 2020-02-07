
@php( $_routeName = Request()->route()->getName() )

<ul id="myTabs" class="nav nav-tabs" role="tablist">
    <li @if( $_routeName == 'rotate.permission.index') class="active" @endif >
        <a href="{{ route('rotate.permission.index') }}" >权限列表</a>
    </li>

    <li  @if( $_routeName == 'rotate.permission.addPage') class="active" @endif>
        <a href="{{ route('rotate.permission.addPage') }}" >新增权限</a>
    </li>
    @if( isset( $info['id']) )
        <li  @if( $_routeName == 'rotate.permission.editPage') class="active" @endif>
            <a href="{{ route('rotate.permission.editPage' , ['infoId'=>$info['id']]) }}" >编辑权限</a>
        </li>
    @endif

    <li  @if( $_routeName == 'rotate.permission.types') class="active" @endif>
        <a href="{{ route('rotate.permission.types') }}" >分类列表</a>
    </li>
    <li  @if( $_routeName == 'rotate.permission.addTypePage') class="active" @endif>
        <a href="{{ route('rotate.permission.addTypePage') }}" >新增分类</a>
    </li>
    @if( isset( $permissionType['id']) )
        <li  @if( $_routeName == 'rotate.permission.editTypePage') class="active" @endif>
            <a href="{{ route('rotate.permission.editTypePage' , ['infoId'=>$permissionType['id']]) }}" >编辑分类</a>
        </li>
    @endif

</ul>