@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::permission.inc.inc")
@endsection
@section('car-body')
    <table class="table table-hover">
        <colgroup>
            <col width="120"/>
            <col width="160"/>
            <col width="160"/>
            <col width=""/>
            <col width=""/>
            <col width=""/>
            <col width=""/>
        </colgroup>
        <thead>
        <tr>

            <th>操作</th>
            <th>权限名称</th>
            <th>显示名称</th>
            <th>权限分类</th>

            <th>介绍</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $datas as $key=>$item)
            <tr>
                <td>
                    <div class="btn-group">

                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.permission.editPage' , ['infoId'=>$item['id'] ]) }}"
                           title="编辑" data-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger act-stars-remove" href="{{ route( 'rotate.permission.remove' , ['infoId'=>$item['id'] ]) }}" title="删除" data-toggle="tooltip">
                            <i class="mdi mdi-window-close"></i>
                        </a>
                    </div>
                </td>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['display_name'] }}</td>
                <td>{{ $item['typeInfo']['title']?? '-' }}</td>
                <td>{{ $item['description'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @include("StarsPeace::inc.pagination")
@endsection
