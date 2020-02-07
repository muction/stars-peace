@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::nav.menu.inc")
@endsection

@section("car-body")
    <style type="text/css">

        .columns-label{
            width: 150px;
            font-weight: normal;
        }
        .mouse-hover-card:hover{
            background-color: #F1FBFB;
        }

    </style>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="160">操作</th>
            <th>绑定名称</th>
        </tr>
        </thead>
        <tbody>
            @foreach( $info->binds as $bind )
                <tr>
                    <td>
                        <a href="{{ route(  'rotate.menu.bind.edit' , ['navId'=> $info['nav_id'] ,'menuId'=> $info['id'] ,'bindId'=>$bind['id'] ]) }}">修改</a>
                        <a class="act-stars-remove" href="{{ route(  'rotate.menu.bind.remove' , ['navId'=> $info['nav_id'] ,'menuId'=> $info['id'] ,'bindId'=>$bind['id'] ]) }}">删除</a>
                    </td>
                    <td>{{ $bind['title'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>


    <form method="post" action="{{ route( 'rotate.menu.bind.storage' , ['navId'=>$info['nav_id'] ,'menuId'=>$info['id']]) }}" >
        @foreach( $sheets as $ks=>$val )
        <div class="row">
            @csrf
            @foreach($val as $key=> $item)
                <div class="col-sm-10 col-lg-3">
                    @php( $_sheetName= $item['info']['sheetClassName'] )
                    <input type="hidden" name="sheets[]" value="{{ $_sheetName }}" />
                    <input type="hidden" name="sheets_table_name-{{$_sheetName}}" value="{{ $item['info']['tableName'] }}" />

                    <div class="card mouse-hover-card">
                        <div class="card-header">
                            <p>
                                <button type="submit" class="btn btn-info btn-sm">保存所有</button>
                                <span title="{{ $_sheetName }}" data-toggle="tooltip">
                                {{ $item['info']['sheetName'] }}
                            </span>
                            </p>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                <span >绑定名称
                                    <input class="form-control" type="text" name="bind-name-{{$_sheetName}}" style="" value="">
                                </span>
                                </li>
                                <li class="list-group-item">
                                <span >绑定别名(例如：最新单条数据：single.xxx  ， 所有：list.xxx ，分页：paginate.xxx) <input class="form-control" type="text" name="bind-alias-{{$_sheetName}}" style="" value="">
                                </span>
                                </li>

                            </ul>

                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a data-toggle="tab" href="#{{$_sheetName}}sheet-require">必填</a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#{{$_sheetName}}sheet-list">列表</a>
                                </li>
                                <li class="nav-item">
                                    <a data-toggle="tab" href="#{{$_sheetName}}sheet-search">搜索</a>
                                </li>
                            </ul>


                            <div class="tab-content">
{{--  必填字段 --}}
                                <div class="tab-pane fade active in" id="{{$_sheetName}}sheet-require">
                                    @foreach($item['columns'] as $column)
                                        <label class="columns-label">
                                            <input name="column-required-{{$_sheetName}}[]" type="checkbox" checked="checked" class="checkbox-child" value="{{$column['db_name']}}">
                                            <span class="column-title">{{$column['title']}}</span>
                                        </label>
                                    @endforeach

                                </div>
{{-- 列表 --}}
                                <div class="tab-pane fade" id="{{$_sheetName}}sheet-list">
                                    @foreach($item['columns'] as $column)
                                        <label class="columns-label">
                                            <input name="column-list-{{$_sheetName}}[]" checked="checked" type="checkbox" class="checkbox-child" value="{{$column['db_name']}}">
                                            <span class="column-title">{{$column['title']}}</span>
                                        </label>
                                    @endforeach
                                </div>

{{--  搜索 --}}
                                <div class="tab-pane fade" id="{{$_sheetName}}sheet-search">

                                    @php( $unSupportSearchColumn = [ \Stars\Peace\Foundation\SheetSheet::SUPPORT_COLUMN_DATETIME , \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TIME] )
                                    @foreach($item['columns'] as $column)
                                        <label class="columns-label" >
                                            <input name="column-search-{{$_sheetName}}[]"  @if(!in_array( $column['plug'] ,$unSupportSearchColumn) ) checked="checked" @else disabled="disabled" title="时间类型禁止like查询" @endif type="checkbox" class="checkbox-child" value="{{$column['db_name']}}">
                                            <span class="column-title">{{$column['title']}}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach



        </div>
        @endforeach

    </form>
@endsection
