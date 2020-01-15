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
                                <button class="btn btn-sm btn-warning" type="button" id="btn-apply-change">应用更改</button>
                                <button class="btn btn-sm btn-default" type="button" id="btn-back-last-version">回滚</button>

                                <label class="label label-info" title="模板数据来源">{{$templateDataSource}}</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <ol>
                                    @foreach($templateFiles as  $index=>$filePathName)
                                        @if( strstr( $filePathName , $validNavInfo['theme'] ) )
                                            <li>
                                                <a href="{{ route('rotate.template.index' , ['nav'=>$validNavId ,'template_name'=>$filePathName ]) }}"
                                                   style="@if($templateName == $filePathName) color:red @endif "
                                                   style="line-height: 10px"
                                                >
                                                   {{ $filePathName }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ol>
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

            $('#menus').css({height : documentHeight-100});

            $(function(){
                let templateName = "{{ $templateName }}";
                let validNavId = "{{$validNavId}}";
                let token = "{{csrf_token()}}";
                var hasRequest=null;
                //应用本次更改
                $('#btn-apply-change').click(function(){

                    if( confirm('您即将应用本次更改，将会替换现有模板文件，是否继续?') && confirm("确定继续操作?")){
                        if( hasRequest !=null ){
                            hasRequest.abort();
                        }

                        hasRequest= $.ajax({
                            url : "{{ route('rotate.template.apply') }}",
                            type:"post" ,
                            data : { templateName : templateName , validNavId:validNavId , templateContent : editor.getValue() , _token : token },
                            dataType : "json" ,
                            error: function(){
                                alert("应用时发生错误，您可以稍后尝试重试，如果错误继续存在，请联系站点管理员~");
                            },
                            success : function( e ){
                                if( e.error == 0){
                                    alert( e.msg );
                                }else{
                                    alert( '操作失败了' );
                                }
                            }
                        });
                    }
                });

                $('#btn-back-last-version').click(function(){
                    if( confirm('确定要回滚到上次版本吗? 是否继续?') && confirm("确定继续操作?")){
                        if( hasRequest !=null ){
                            hasRequest.abort();
                        }

                        hasRequest= $.ajax({
                            url : "{{ route('rotate.template.rollBack') }}",
                            type:"post" ,
                            data : { templateName : templateName , validNavId:validNavId , templateContent : editor.getValue() , _token : token },
                            dataType : "json" ,
                            error: function(){
                                alert("应用时发生错误，您可以稍后尝试重试，如果错误继续存在，请联系站点管理员~");
                            },
                            success : function( e ){
                                if( e.error == 0){
                                    alert( e.msg );
                                }else{
                                    alert( '操作失败了' );
                                }
                            }
                        });
                    }
                });

            });
        </script>
@endsection
