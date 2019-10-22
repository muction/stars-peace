@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::role.inc.inc")
@endsection
@section('car-body')
    <table class="table table-bordered">
        <colgroup>
            <col width="120"/>
            <col width="160"/>
            <col width="160"/>
            <col width=""/>
        </colgroup>
        <thead>
        <tr>

            <th>操作</th>
            <th>角色名称</th>
            <th>显示名称</th>
            <th>介绍</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $datas as $key=>$item)
            <tr>

                <td>
                    <div class="btn-group">
                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.role.bindRolePage', ['infoId'=>$item['id'] ]) }}"
                           title="设置权限" data-toggle="tooltip">
                            <i class="mdi mdi-all-inclusive"></i>
                        </a>

                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.role.editPage' , ['infoId'=>$item['id'] ]) }}"
                           title="编辑" data-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger act-stars-remove" href="{{ route( 'rotate.role.remove' , ['infoId'=>$item['id'] ]) }}" title="删除" data-toggle="tooltip">
                            <i class="mdi mdi-window-close"></i>
                        </a>
                    </div>
                </td>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['display_name'] }}</td>
                <td>{{ $item['description'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @include("StarsPeace::inc.pagination")
@endsection