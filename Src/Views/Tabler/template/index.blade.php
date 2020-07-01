@extends("StarsPeace::iframe")


@section("page-head")
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/lib/codemirror.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/mdn-like.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/codemirror/theme/the-matrix.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("static/stars/plugs/select2/css/select2.css") }}"/>
    <script type="text/javascript" src="{{ asset("static/stars/plugs/select2/js/select2.js") }}"></script>

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
    </style>
@endsection

@section("car-head")

    @php( $_routeName = Request()->route()->getName() )
    <ul class="nav nav-tabs">
        <li>
            <a class="btn-back-last-version">回滚版本</a>
        </li>
        <li>
            <a  id="btn-apply-change">应用更改</a>
        </li>
    </ul>
@endsection

@section('car-body')
    <div class="row" style="height: 100%" >
        <div class="col-md-2" style="padding-right: 0px;padding-left: 0px">

            <form id="templateForm" method="get">
                <select id="nav-select" name="nav" class="select23" style=" width: 100px;margin-top: 8px;margin-left: 15px">
                    @foreach($articleNavs as $nav)
                        <option value="{{ $nav['id'] }}"
                                @if( $validNavId ==  $nav['id'] )
                                selected="selected"
                            @endif >
                            {{ $nav['title'] }}
                        </option>
                    @endforeach
                </select>

                <select id="nav-menu-select" class="select23" name="template_name" style="width: 150px">
                    @component('StarsPeace::component.template-select-menu', ['datas'=> $navMenus ,'templateName'=>$templateName ,'validNavId'=>$validNavId ]) @endcomponent
                </select>
            </form>

            <div id="box-height" class="list-group" style="margin-top: 10px;margin-right: 14px">
                @foreach($templateFiles as  $index=>$filePathName)
                    @if(isset($validNavInfo) && strstr( $filePathName , $validNavInfo['theme'] ) && strstr( $filePathName , '.blade.php' ) )

                            <a href="{{ route('rotate.template.index' , ['nav'=>$validNavId ,'template_name'=>$filePathName ]) }}"
                               style="line-height: 10px;text-align: right"
                               class="list-group-item  @if($templateName == $filePathName) active  @endif"
                            >
                                {{ $filePathName }}
                            </a>

                    @endif
                @endforeach
            </div>
        </div>
        <div class="col-md-10" style="padding-left: 0px;">
            <textarea name="templateContent" id="code">{{$templateContent}}</textarea>
        </div>
    </div>

    @include("StarsPeace::template.modal")

    <script type="text/javascript">
        $(function () {
            $("select").change(function () {
                $('#templateForm').submit();
            });

            $('.select23').select2();

        });


        var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
            lineNumbers: true,
            styleActiveLine: true,
            matchBrackets: true,
            lineWrapping: true,
            theme: "mdn-like"
        });

        var documentHeight = $('#box-height').height();
        editor.setSize('auto', documentHeight > 700 ? documentHeight : 700);

        //版本操作
        $(function () {
            let templateName = "{{ $templateName }}";
            let validNavId = "{{$validNavId}}";
            let token = "{{csrf_token()}}";
            var hasRequest = null;
            //应用本次更改
            $('#btn-apply-change').click(function () {

                if(!templateName){
                    alert("请选择模板文件");
                    return false;
                }

                if (confirm('您即将应用本次更改，将会替换现有模板文件，是否继续?') && confirm("确定继续操作?")) {
                    if (hasRequest != null) {
                        hasRequest.abort();
                    }

                    hasRequest = $.ajax({
                        url: "{{ route('rotate.template.apply') }}",
                        type: "post",
                        data: {
                            templateName: templateName,
                            validNavId: validNavId,
                            templateContent: editor.getValue(),
                            _token: token
                        },
                        dataType: "json",
                        error: function () {
                            alert("应用时发生错误，您可以稍后尝试重试，如果错误继续存在，请联系站点管理员~");
                        },
                        success: function (e) {
                            //
                            if (e.error == 0) {
                                alert(e.msg);
                                window.location.reload();
                            } else {
                                alert('操作失败了');
                            }
                        },
                        complete:function(){
                            try{
                                parent. pageProgress ('hide');
                            }catch (e) {

                            }
                        }
                    });
                }
            });

            //回归版本
            $('#modal-close').click(function(){
                $('#templateVersionBody').html( "" );
            });

            $('.btn-back-last-version').click(function () {

                if(!templateName){
                    alert("请选择模板文件");
                    return false;
                }

                if (confirm('确定要回滚吗?') ) {

                    if (hasRequest != null) {
                        hasRequest.abort();
                    }

                    //是否有选择到要回归的版本
                    let backVersion = $('input[name="version_id"]:checked').val();

                    hasRequest = $.ajax({
                        url: "{{ route('rotate.template.rollBack') }}",
                        type: "post",
                        data: {
                            backVersion : backVersion,
                            templateName: templateName,
                            validNavId: validNavId,
                            _token: token
                        },
                        dataType: "json",
                        error: function () {
                            alert("应用时发生错误，您可以稍后尝试重试，如果错误继续存在，请联系站点管理员~");
                        },
                        success: function (e) {
                            if (e.error === 0) {

                                if( !backVersion ){
                                    let html = "";
                                    let max = e.body.length;
                                    for(let i=0; i< max ; i++){
                                        let item = e.body[i];
                                        let itemStatus=  parseInt( item.status );
                                        let isUseIng = itemStatus === 1? " 使用中" :"";

                                        if(isUseIng === 1){
                                            html +='<div class="radio">' +
                                                ' <label>' +
                                                ' <input type="radio" name="version_id" value="'+ item.id +'" />' +
                                                ' 创建时间：'+ item.created_at+ '&nbsp;版本号：' + item.id + ' ' + isUseIng +
                                                '</label>' +
                                                ' </div>';
                                        }else{
                                            html +='<div class="radio">' +
                                                ' <label>' +
                                                ' <input type="radio" disabled="disabled" />' +
                                                ' 创建时间：'+ item.created_at+ '&nbsp;版本号：' + item.id + ' ' + isUseIng +
                                                '</label>' +
                                                ' </div>';
                                        }

                                    }

                                    $('#templateVersionBody').html( html );
                                    $('#templateBackVersion').modal('show');
                                }else{
                                    $('#templateVersionBody').html( "" );
                                    $('#templateBackVersion').modal('hide');
                                    alert("操作成功");
                                }
                            } else {
                                alert('操作失败了');
                            }
                        },
                        complete:function(){
                            try{
                                parent. pageProgress ('hide');
                            }catch (e) {

                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
