@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::permission.inc.inc")
@endsection
@section('car-body')
    <table class="table table-bordered">
        <colgroup>
            <col width="120"/>
            <col width=""/>
            <col width=""/>
        </colgroup>
        <thead>
        <tr>

            <th>操作</th>
            <th>分类名称</th>
            <th>排序</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $datas as $key=>$item)
            <tr>
                <td>
                    <div class="btn-group">

                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.permission.editTypePage' , ['infoId'=>$item['id'] ]) }}"
                           title="编辑" data-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger act-stars-remove" href="{{ route( 'rotate.permission.typeRemove' , ['infoId'=>$item['id'] ]) }}" title="删除" data-toggle="tooltip">
                            <i class="mdi mdi-window-close"></i>
                        </a>
                    </div>
                </td>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['order'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @include("StarsPeace::inc.pagination")
@endsection