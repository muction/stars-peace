@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::slide.inc.inc")
@endsection
@section('car-body')
    <table class="table table-hover">
        <colgroup>
            <col width="120"/>
            <col width="160"/>
            <col width="120"/>
            <col width="120"/>
            <col width="120"/>
            <col width=""/>
        </colgroup>
        <thead>
        <tr>

            <th>操作</th>
            <th>名称</th>
            <th>分类</th>
            <th>排序</th>
            <th>预览</th>
            <th>介绍</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $datas as $key=>$item)
            <tr>
                <td>
                    <div class="btn-group">

                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.slide.editPage' , [ 'typeId'=>$item['slide_type_id'], 'infoId'=>$item['id'] ]) }}"
                           title="编辑" data-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-danger act-stars-remove" href="{{ route( 'rotate.slide.remove' , [ 'typeId'=>$item['slide_type_id'], 'infoId'=>$item['id'] ]) }}" title="删除" data-toggle="tooltip">
                            <i class="mdi mdi-window-close"></i>
                        </a>
                    </div>
                </td>
                <td>{{ $item['title'] }}</td>
                <td>{{ $item['type']['title']?? '-' }}</td>
                <td>{{ $item['order'] }}</td>
                <td> @if(isset($item['attachment']))
                        <a href="/storage/{{$item['attachment']['save_file_path']}}/{{$item['attachment']['save_file_name']}}" target="_blank">
                            <img width="100" src="/storage/{{$item['attachment']['save_file_path']}}/{{$item['attachment']['save_file_name']}}">
                        </a>
                    @else -  @endif</td>
                <td>{{ $item['summary'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @include("StarsPeace::inc.pagination")
@endsection
