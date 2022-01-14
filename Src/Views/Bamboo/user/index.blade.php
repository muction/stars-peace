@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::user.inc.inc")
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
        </colgroup>
        <thead>
        <tr>

            <th>操作</th>
            <th>登录名称</th>
            <th>邮箱</th>
            <th>部门</th>
            <th>最后登录时间</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $datas as $key=>$item)
            <tr>

                <td>
                    <div class="btn-group">

                        <a class="btn btn-xs btn-default" href="{{ route( 'rotate.user.editPage' , ['infoId'=>$item['id'] ]) }}"
                           title="编辑" data-toggle="tooltip">
                            <i class="mdi mdi-pencil"></i>
                        </a>
                        <a class="btn btn-xs btn-default act-stars-remove" href="{{ route( 'rotate.user.remove' , ['infoId'=>$item['id'] ]) }}" title="删除" data-toggle="tooltip">
                            <i class="mdi mdi-delete-forever"></i>
                        </a>
                    </div>
                </td>
                <td>{{ $item['username'] }}</td>
                <td>{{ $item['email'] }}</td>
                <td>{{ $item['branch'] }}</td>
                <td>{{ $item['last_login_time'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


    @include("StarsPeace::inc.pagination")
@endsection
