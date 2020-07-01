
<div class="panel panel-default stars-plug">
    <div class="panel-body">
        <textarea class="_content_editor" id="editor_{{$column['db_name']}}" name="{{$column['db_name']}}">{{ old($column['db_name'] , isset($column['now_value']) ? $column['now_value'] : '')  }}</textarea>

        <script type="text/javascript">
            KindEditor.create("#editor_{{$column['db_name']}}",
                {
                    width: "100%",
                    height: 300,
                    filterMode : false,
                    uploadJson : "{{route('rotate.attachment.upload', ['client'=>'KindEditor'])}}" ,
                    fileManagerJson : "{{ route('rotate.attachment.manager') }}",
                    allowFileManager : true ,
                    filePostName : 'uploader',
                    items: [
                        'source', 'fullscreen' ,'undo', 'redo', '|',
                        'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|',
                        'justifyleft', 'justifycenter', 'justifyright', 'justifyfull','|',
                        'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', '|',
                        'superscript', 'clearhtml', 'selectall', '|',
                        'formatblock', 'fontname', 'fontsize',  'forecolor', '/', '|', 'hilitecolor', 'bold',
                        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
                        'flash', 'media', 'insertfile', 'table', '|',
                        'anchor', 'link', 'unlink'
                    ],
                    extraFileUploadParams:{
                        menuId : "{{$menuId}}" ,
                        dbName : "{{$column['db_name']}}",
                        bindId : "{{$bindId}}",
                        _token : '{{csrf_token() }}'

                    }
                });
        </script>
    </div>

</div>
