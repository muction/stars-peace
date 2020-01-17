@extends("StarsPeace::iframe")


@section("page-head")
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/lib/codemirror.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/night.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/mdn-like.css") }}"/>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/lib/codemirror.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/addon/selection/active-line.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/addon/edit/matchbrackets.js") }}"></script>


    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/stylus/stylus.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/css/css.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/javascript/javascript.js") }}"></script>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/codemirror/mode/php/php.js") }}"></script>

    <style type="text/css">
    </style>
@endsection

@section("car-head")

    @php( $_routeName = Request()->route()->getName() )
    <ul  class="nav nav-tabs" >
        <li>
            <form id="templateForm" method="get">

                <select id="nav-select" name="nav"  style="margin-top: 8px;margin-left: 15px">
                    @foreach($articleNavs as $nav)
                        <option value="{{ $nav['id'] }}"
                                @if( $validNavId ==  $nav['id'] )
                                selected="selected"
                            @endif >
                            {{ $nav['title'] }}
                        </option>
                    @endforeach
                </select>

                <select id="nav-menu-select" name="template_name" style="width: 150px">
                    @component('StarsPeace::component.template-select-menu', ['datas'=> $navMenus ,'templateName'=>$templateName ,'validNavId'=>$validNavId ]) @endcomponent
                </select>
            </form>

        </li>

        <li>
            <div style="margin: 10px;" class="text-primary"> {{ $templateDataSource ? $templateDataSource.' :'.$templateName : '请选择要编辑的模板' }} </div>
        </li>

        <li>
            <div class="btn-group" role="group" style="margin-top:2px; margin-left: 5px">
                <button type="button" class="btn btn-default" id="btn-back-last-version">向上回滚</button>
                <button type="button" class="btn btn-default" id="btn-apply-change">应用更改</button>
            </div>
        </li>
    </ul>
@endsection

@section('car-body')
    <div class="row" style="height: 100%">
        <div class="col-md-3" style="padding-right: 0px;padding-left: 0px">
            <ol>
                @foreach($templateFiles as  $index=>$filePathName)
                    @if( strstr( $filePathName , $validNavInfo['theme'] ) )
                        <li>
                            <a href="{{ route('rotate.template.index' , ['nav'=>$validNavId ,'template_name'=>$filePathName ]) }}"
                               style="@if($templateName == $filePathName) color:green @else color:#9C9E9E @endif "
                               style="line-height: 10px" >
                                {{ $filePathName }}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
        <div class="col-md-9" style="padding-left: 0px;">
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
                theme: "mdn-like"
            });

            var documentHeight = $(document).height();
            editor.setSize( 'auto', documentHeight-100 );
            //
            // $('#menus').css({height : documentHeight-100});

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
                                    window.location.reload();
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
