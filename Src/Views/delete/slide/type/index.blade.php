@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::slide.inc.inc")
@endsection
@section('car-body')
    <table class="table table-hover">
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
                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.slide.index' , ['typeId'=>$item['id'] ]) }}"
                           title="幻灯片" data-toggle="tooltip">
                            <i class="mdi mdi-folder-multiple-image"></i>
                        </a>
                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.slide.editTypePage' , ['infoId'=>$item['id'] ]) }}"
                           title="编辑" data-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger act-stars-remove" href="{{ route( 'rotate.slide.typeRemove' , ['infoId'=>$item['id'] ]) }}" title="删除" data-toggle="tooltip">
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
