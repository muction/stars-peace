
@extends("StarsPeace::iframe")


@section('page-head')

    <link href="{{ asset('static/stars/js/bootstrap-datepicker/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('static/stars/js/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            $('.js-datepicker').each(function() {
                var $input = jQuery(this);
                $input.datepicker({
                    weekStart: 1,
                    autoclose: true,
                    todayHighlight: true,
                    language: 'zh-CN',
                    format: 'yyyy-mm-dd',
                });
            });
        });
    </script>

    <style type="text/css">
        .stars-pagination ul{
            margin: 0px !important;
        }
        .stars-article-search-label{
            font-weight: normal;
        }
        .stars-label-interval{
            margin-left: 10px;
        }
    </style>
@endsection

@section("car-head")
    @include("StarsPeace::article.index.inc")
@endsection

@section('car-body')


        <div style="margin-top: 5px;margin-right:20px ; margin-bottom: 10px">
            <form class="form-inline"
                  action="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindId ]) }}"
                  method="get" >

                <div class="form-group">
                    <label class="stars-article-search-label stars-label-interval" for="example-if-keyword">创建时间：</label>
                    <input class="form-control input-sm js-datepicker" type="text" style="width: 100px" readonly="readonly" name="start_time" value="{{ $startTime }}" placeholder="开始时间">
                    -
                    <input class="form-control input-sm js-datepicker" type="text" style="width: 100px" readonly="readonly" name="end_time" value="{{ $endTime }}" placeholder="结束时间">
                </div>

                <div class="form-group">
                    <label class="stars-article-search-label" for="example-if-keyword">&nbsp;&nbsp;关键字：</label>
                    <input class="form-control input-sm" type="text" id="example-if-keyword" name="keyword" value="{{$keyword}}" placeholder="关键字..">
                </div>

                <div class="form-group">
                    <button class="btn btn-default btn-sm" type="submit">搜索</button>
                    <a href="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindId ]) }}" class="btn btn-default btn-sm" type="submit">清空</a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
        <table class="table table-bordered table-condensed table-hover table-striped">
            <thead>
            <tr>
                <th width="100" align="center" style="text-align: center">
                    操作
                </th>
                <th width="100" align="center" style="text-align: center">
                    ID
                </th>
                @foreach( $bindListColumns as $_listColumnName )
                    @foreach( $sheetColumns as $_sheetColumn )
                        @if( $_listColumnName == $_sheetColumn['db_name'] )
                            <th style="text-align: center">{{$_sheetColumn['title']}}</th>
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
                    <td style="text-align: center">
                        {{$valueItem['id']}}
                    </td>
                    @foreach( $bindListColumns as $_listColumnName )
                        @if( isset( $sheetColumns[$_listColumnName]['plug'] ) )
                            <td style="text-align: center">
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

@endsection
