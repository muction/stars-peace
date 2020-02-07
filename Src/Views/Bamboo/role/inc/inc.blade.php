
@php( $_routeName = Request()->route()->getName() )

<ul id="myTabs" class="nav nav-tabs" role="tablist">
    <li @if( $_routeName == 'rotate.role.index') class="active" @endif >
        <a href="{{ route('rotate.role.index') }}" >角色列表</a>
    </li>
    <li  @if( $_routeName == 'rotate.role.addPage') class="active" @endif>
        <a href="{{ route('rotate.role.addPage') }}" >新增角色</a>
    </li>

    @if( isset($info['id']) )
        <li  @if( $_routeName == 'rotate.role.editPage') class="active" @endif>
            <a href="{{ route('rotate.role.editPage' , ['infoId'=>$info['id']]) }}" >编辑角色</a>
        </li>
    @endif

    @if( isset($role['id']) )
        <li  @if( $_routeName == 'rotate.role.bindRolePage') class="active" @endif>
            <a href="{{ route('rotate.role.bindRolePage' , ['infoId'=>$role['id']]) }}" >配置权限</a>
        </li>
    @endif
</ul>