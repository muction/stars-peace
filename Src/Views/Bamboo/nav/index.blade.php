@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::nav.menu.inc")
@endsection
@section('car-body')
    <table class="table table-hover">
        <colgroup>
            <col width="40"/>
            <col width="120"/>
            <col width="160"/>
        </colgroup>
        <thead>
        <tr>

            <th>操作</th>
            <th>名称</th>
            <th>介绍</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $datas as $key=>$item)
            <tr>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.menu.index' , ['navId'=>$item['id'] ]) }}"
                           title="子菜单管理" data-toggle="tooltip">
                            <i class="mdi mdi-menu"></i>
                        </a>
                        <a class="btn btn-xs btn-default act-stars-remove" href="{{ route( 'rotate.nav.menu.copy' , ['navId'=>$item['id'] ]) }}" title="复制菜单信息到" data-toggle="tooltip">
                            <i class="mdi mdi-selection"></i>
                        </a>
                        <a class="btn btn-xs btn-default" href="{{ route('rotate.nav.edit' , ['navId'=>$item['id'] ]) }}"
                           title="编辑" data-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-default act-stars-remove" href="{{ route('rotate.nav.remove' , ['navId'=>$item['id'] ]) }}" title="删除" data-toggle="tooltip">
                            <i class="mdi mdi-delete-forever"></i>
                        </a>
                    </div>
                </td>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['remark'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @include("StarsPeace::inc.pagination")
@endsection
