
@extends("StarsPeace::iframe")


@section('page-head')

    <style type="text/css">
        .stars-pagination ul{
            margin: 0px !important;
        }
    </style>
@endsection

@section("car-head")
    @include("StarsPeace::article.index.inc")
@endsection

@section('car-body')

<div style="margin-top: 5px;">

        <div style="margin-top: 5px;margin-right:20px ;">
            <form class="form-inline"
                  action="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindId ]) }}"
                  method="get" >
                <div class="form-group">
                    <label class="sr-only" for="example-if-keyword">关键字</label>
                    <input class="form-control input-sm" type="text" id="example-if-keyword" name="keyword" value="{{$keyword}}" placeholder="关键字..">
                </div>
                <div class="form-group">
                    <button class="btn btn-default btn-sm" type="submit">搜索</button>
                </div>
            </form>
        </div>


    <div class="tab-content">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th width="100" align="center">
                    操作
                </th>
                @foreach( $bindListColumns as $_listColumnName )
                    @foreach( $sheetColumns as $_sheetColumn )
                        @if( $_listColumnName == $_sheetColumn['db_name'] )
                            <th >{{$_sheetColumn['title']}}</th>
                            @break
                        @endif
                    @endforeach
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($datas as $_valueIndex=>$valueItem)
                <tr>
                    <td align="center">
                        @include("StarsPeace::plugs.list.action")
                    </td>

                    @foreach( $bindListColumns as $_listColumnName )
                        @if( isset( $sheetColumns[$_listColumnName]['plug'] ) )
                            <td>
                                @switch( $sheetColumns[$_listColumnName]['plug'] )
                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TEXT )
                                    @include("StarsPeace::plugs.list.text")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_SELECT )
                                    @include("StarsPeace::plugs.list.select")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_EDITOR )
                                    @include("StarsPeace::plugs.list.editor")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_CROPPER )
                                    @include("StarsPeace::plugs.list.cropper")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_RADIOS )
                                    @include("StarsPeace::plugs.list.radios")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_CHECKBOX )
                                    @include("StarsPeace::plugs.list.checkboxs")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_UPLOAD )
                                    @include("StarsPeace::plugs.list.upload")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_NUMBER )
                                    @include("StarsPeace::plugs.list.number")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TIME )
                                    @include("StarsPeace::plugs.list.timer")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TEXTAREA )
                                    @include("StarsPeace::plugs.list.textarea")
                                    @break

                                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_PASSWORD )
                                    @include("StarsPeace::plugs.list.password")
                                    @break

                                    @default
                                    --
                                @endswitch
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="stars-pagination">
            @include( "StarsPeace::inc.pagination" )
        </div>
    </div>

</div>
@endsection
