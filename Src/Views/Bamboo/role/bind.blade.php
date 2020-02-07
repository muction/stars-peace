@extends("StarsPeace::iframe")


@section( 'car-head' )
    @include("StarsPeace::role.inc.inc")
@endsection

@section('car-body')
    <form class="form-horizontal" method="post" action="{{ route( 'rotate.role.bindRoleStorage' ,['infoId'=> isset($role['id']) ? $role['id'] : null ] ) }}">
        @csrf

            <div class="form-group">
                <label for="example-text-input">角色名称</label>
                <input class="form-control" type="text" name="role-input" placeholder="角色名称">
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>
                            <label class="lyear-checkbox checkbox-primary">
                                <input name="checkbox" type="checkbox" id="check-all">
                                <span> 全选</span>
                            </label>
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach( $allTypePermissions as $item )
                        <tr>
                            <td class="p-l-20">
                                <label class="">
                                    <span>{{$item['title']}}</span>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-l-40">
                                @foreach( $item['permissions']  as $permission )
                                    <label class="lyear-checkbox checkbox-primary checkbox-inline">
                                        <input name="permissions[]" type="checkbox" @if( in_array($permission['id'], $role['permissions']) ) checked="checked" @endif class="checkbox-child" dataid="id-{{$permission['id']}}" value="{{$permission['id']}}">
                                        <span>{{$permission['display_name']}}</span>
                                    </label>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">保存</button>
            </div>
        </div>
    </form>
@endsection