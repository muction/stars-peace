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

    </style>
    <table class="table table-hover">
        <thead>
        <tr>
            <th width="160">操作</th>
            <th>绑定名称</th>
        </tr>
        </thead>
        <tbody>

            <tr>
                <td>
                    <a href="{{ route(  'rotate.menu.bind.edit' , ['navId'=> $info['nav_id'] ,'menuId'=> $info['id'] ,'bindId'=>$bind['id'] ]) }}">修改</a>
                    <a class="act-stars-remove" href="{{ route(  'rotate.menu.bind.remove' , ['navId'=> $info['nav_id'] ,'menuId'=> $info['id'] ,'bindId'=>$bind['id'] ]) }}">删除</a>
                </td>
                <td>{{ $bind['title'] }}</td>
            </tr>

        </tbody>
    </table>

    <form method="post" action="{{ route( 'rotate.menu.bind.storage' , ['navId'=>$info['nav_id'] ,'menuId'=>$info['id'] ,'bindId'=>$bind['id'] ]) }}" >
        @csrf

            @foreach($sheets as $key=> $item)

                @php( $_bindOptions= json_decode($bind['options'] , true ) )

                @php( $_sheetName= $item['info']['sheetClassName'] )
                @if( $bind['sheet_name'] != $_sheetName )
                    @continue
                @endif



                <input type="hidden" name="sheets[]" value="{{ $_sheetName }}" />
                <input type="hidden" name="sheets_table_name-{{$_sheetName}}" value="{{ $item['info']['tableName'] }}" />


                    <p>
                        <button type="submit" class="btn btn-success">更新</button>
                        <span title="{{ $_sheetName }}" data-toggle="tooltip">
                            {{ $item['info']['sheetName'] }}
                        </span>
                    </p>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span >绑定名称 <input class="form-control" type="text" name="bind-name-{{$_sheetName}}" style="width: 200px" value="{{ $bind['title'] }}"> </span>
                        </li>
                        <li class="list-group-item">
                            <span >绑定别名 <input class="form-control" type="text" name="bind-alias-{{$_sheetName}}" style="width: 200px" value="{{$bind['alias_name']}}"> </span>
                        </li>
                        <li class="list-group-item">
                            <p> <b>列表字段 </b> </p>
                            @foreach($item['columns'] as $column)
                                <label class="columns-label">
                                    <input name="column-list-{{$_sheetName}}[]"
                                           @if(in_array($column['db_name'] ,$_bindOptions['column_list'])) checked="checked" @endif
                                           type="checkbox" class="checkbox-child" value="{{$column['db_name']}}">
                                    <span class="column-title">{{$column['title']}}</span>
                                </label>
                            @endforeach
                        </li>

                        <li class="list-group-item">

                            <p> <b>搜索字段 </b> </p>
                            @foreach($item['columns'] as $column)
                                <label class="columns-label" >
                                    <input name="column-search-{{$_sheetName}}[]"
                                           @if(in_array($column['db_name'] ,$_bindOptions['column_search'])) checked="checked" @endif
                                           type="checkbox" class="checkbox-child" value="{{$column['db_name']}}">
                                    <span class="column-title">{{$column['title']}}</span>
                                </label>
                            @endforeach
                        </li>

                        <li class="list-group-item">

                            <p> <b>必填字段 </b> </p>
                            @foreach($item['columns'] as $column)
                                <label class="columns-label">
                                    <input name="column-required-{{$_sheetName}}[]" type="checkbox"
                                           @if(in_array($column['db_name'] ,$_bindOptions['column_required'])) checked="checked" @endif
                                           class="checkbox-child" value="{{$column['db_name']}}">
                                    <span class="column-title">{{$column['title']}}</span>
                                </label>
                            @endforeach
                        </li>
                    </ul>
            @endforeach

    </form>

@endsection
