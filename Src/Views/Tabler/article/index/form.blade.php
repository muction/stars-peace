@extends("StarsPeace::iframe")


@section("page-head")


    <script type="text/javascript" src="{{asset("static/stars/plugs/kindeditor/kindeditor-all.js")}}"></script>
    <script type="text/javascript" src="{{asset("static/stars/plugs/kindeditor/lang/zh-CN.js")}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset("static/stars/plugs/kindeditor/themes/default/default.css")}}"/>

    <link href="{{ asset('static/stars/plugs/datetimepicker/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('static/stars/plugs/laydate/laydate.js')}}"></script>

    <script type="text/javascript" src="{{ asset("static/stars/plugs/plupload/js/plupload.full.min.js") }}"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/lib/codemirror.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/mdn-like.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/the-matrix.css") }}"/>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/lib/codemirror.js") }}"></script>

    <script type="text/javascript"
            src="{{ asset("static/stars/plugs/codemirror/addon/selection/active-line.js") }}"></script>
    <script type="text/javascript"
            src="{{ asset("static/stars/plugs/codemirror/addon/edit/matchbrackets.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/stylus/stylus.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/css/css.js") }}"></script>
    <script type="text/javascript"
            src="{{ asset("static/stars/plugs/codemirror/mode/javascript/javascript.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/php/php.js") }}"></script>

    <style type="text/css">
        .has-error .stars-plug{
            border-color: #f96868 !important;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075) !important;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075) !important;
        }
    </style>

@endsection

@section("car-head")
    @include("StarsPeace::article.index.inc")
@endsection

@section('car-body')


<form class="form-horizontal" id="articleSubmitForm" action="{{ route( 'rotate.article.articles.storage' , ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindId ,'action'=>$action ,'infoId'=>$infoId ] ) }}" method="post">

    @csrf

    @foreach( $bindSheetInfo['sheet']['columns'] as $column )

        @if( $column['scene'] == \Stars\Peace\Foundation\SheetSheet::SCENE_SYSTEM )
            @continue
        @endif

        <div class="form-group  @error($column['db_name'] ) has-error @enderror">
            <label for="inputEmail3" class="col-sm-1 control-label">
                @if( isset($bindSheetInfo['options']['column_required']) && in_array($column['db_name'] , $bindSheetInfo['options']['column_required']) )
                    <span style="color: red">*</span>
                @endif
                {{$column['title']}}
            </label>
            <div class="col-sm-11">
                @php( $_columnDefaultValue= isset( $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DEFAULT_VALUE] )
           ? ( $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DEFAULT_VALUE] ) :'')

                @switch( $column['plug'] )

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TEXT )
                    @include("StarsPeace::plugs.form.text")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_SELECT )
                    @include("StarsPeace::plugs.form.select")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_EDITOR )
                    @include("StarsPeace::plugs.form.editor")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_CROPPER )
                    @include("StarsPeace::plugs.form.cropper")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_RADIOS )
                    @include("StarsPeace::plugs.form.radios")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_CHECKBOX )
                    @include("StarsPeace::plugs.form.checkboxs")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_UPLOAD )
                    @include("StarsPeace::plugs.form.upload")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_NUMBER )
                    @include("StarsPeace::plugs.form.number")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TIME )
                    @include("StarsPeace::plugs.form.timer")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_TEXTAREA )
                    @include("StarsPeace::plugs.form.textarea")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_PASSWORD )
                    @include("StarsPeace::plugs.form.password")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_MAP_BAIDU )
                    @include("StarsPeace::plugs.form.map.baidu")
                    @break

                    @case( \Stars\Peace\Foundation\SheetSheet::SUPPORT_WIDGET_CODE_MIRROR )
                        @include("StarsPeace::plugs.form.code")
                    @break

                    @default
                    <H4>不支持的插件： {{$column['plug']}}</H4>
                @endswitch
            </div>
        </div>

    @endforeach
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10" >
            <button type="submit" class="btn btn-default" id="articleSubmitFormBtn" >保存</button>
        </div>
    </div>
</form>


{{--  仅限表单页面共用js调用  --}}
    <script type="text/javascript">

        $(function(){



            $('#articleSubmitFormBtn').click(function(){
                try{
                    $(this).text("提交中..");
                    parent.pageProgress('show');
                }catch (e) {

                }
            });
        });
    </script>

@endsection

