@extends("StarsPeace::iframe")


@section("page-head")
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/lib/codemirror.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/night.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/ambiance.css") }}"/>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/lib/codemirror.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/addon/selection/active-line.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/addon/edit/matchbrackets.js") }}"></script>


    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/stylus/stylus.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/css/css.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/javascript/javascript.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/php/php.js") }}"></script>

    <style type="text/css">
        .list-group-item  a{
            color: #3a3432;
        }

        .active  a{
            color: #fff;
        }
    </style>
@endsection

@section('car-body')
        <div class="row">
            <div class="col-md-2">
                <form action="" method="get" id="templateForm">

                    {{--  所有模板文件 --}}
                    <div id="menus" style="overflow-y: scroll">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="nav" class="">
                                    @foreach($articleNavs as $nav)
                                        <option value="{{ $nav['id'] }}" @if($validNavId == $nav['id'] ) selected="selected" @endif>
                                            {{ $nav['title'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-7">
                                <select name="template_name" class="">
                                    @component('StarsPeace::component.template-select-menu', ['datas'=> $navMenus ,'templateName'=>$templateName ]) @endcomponent
                                </select>
                            </div>
                        </div>
                        <div class="" style="height: 10px;"></div>

                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-sm btn-warning" type="button">应用此更改</button>
                                <button class="btn btn-sm btn-default" type="button">回归上次版本</button>
                            </div>
                        </div>
                        <div class="" style="height: 10px;"></div>
                        <div class="list-group">
                            @foreach($templateFiles as  $index=>$filePathName)
                                @if( strstr( $filePathName , $validNavInfo['theme'] ) )

                                    <a href="{{ route('rotate.template.index' , ['nav'=>$validNavId ,'template_name'=>$filePathName ]) }}"
                                       class="list-group-item  @if($templateName == $filePathName) active @endif "
                                       style="line-height: 10px"
                                    >
                                        {{ $filePathName }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-10">
                <textarea name="templateContent" id="code"  >{{$templateContent}}</textarea>
            </div>
        </div>

    <script type="text/javascript">
        $(function(){
            $("select").change(function(){
                $('#templateForm').submit();
            });
        });


    </script>

        <script>

            var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
                lineNumbers: true,
                styleActiveLine: true,
                matchBrackets: true,
                lineWrapping: true,
                theme: "ambiance"
                //mode:  "php" ,
                //setSize : {width: 200, height: 600 }
            });

            var documentHeight = $(document).height();
            editor.setSize( 'auto', documentHeight );

            $('#menus').css({height : documentHeight});

        </script>
@endsection
