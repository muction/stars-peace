<div >
    @php( $__sheetCropperUploadFieldOption = isset($column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_UPLOAD ]) ? $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_UPLOAD ] : [] )

    <div class="panel panel-default stars-plug">
        <div class="panel-heading">
            <input type="hidden" id="save_attachment_id_{{$column['db_name']}}" name="{{ $column['db_name'] }}"
                   value="{{ old($column['db_name'] , isset($column['now_value'][0] ) ? $column['now_value'][0]['id'] : '') }}"/>
            <button type="button" id="upload_btn_{{$column['db_name']}}" value="选择文件">选择文件</button>
            <button type="button" class="btn btn-xs btn-info btn-default"
                    aria-hidden="true" id="cut_btn_{{$column['db_name']}}">裁剪</button>
        </div>
        <div class="panel-body">
            <div>
                @if( isset( $__sheetCropperUploadFieldOption['notice']) && $__sheetCropperUploadFieldOption['notice'] )
                    <p class="text-muted">
                        {{$__sheetCropperUploadFieldOption['notice']}}
                    </p>
                @endif
                <p class="text-muted">
                    支持文件大小: {{ isset($__sheetCropperUploadFieldOption['maxSize']) ? $__sheetCropperUploadFieldOption['maxSize'].'MB' : '未配置' }}
                </p>

                <p class="text-muted">
                    支持文件格式: {{  isset($__sheetCropperUploadFieldOption['fileType']) ? implode(', ' , $__sheetCropperUploadFieldOption['fileType']) : '未配置'  }}
                </p>
            </div>

            <div id="preview_upload_file_{{$column['db_name']}}" style="cursor: pointer">
                @if( isset($column['now_value']) && $column['now_value'])
                    @foreach( $column['now_value'] as $__nowValue )

                        <a target="_blank" class="cutImageBtn" href="/storage/{{$__nowValue['save_file_path']}}/{{$__nowValue['save_file_name']}}" title="单击预览图片">
                            /storage/{{$__nowValue['save_file_path']}}/{{$__nowValue['save_file_name']}}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    $(function () {
        let cropperHandleUrl = "{{ route('rotate.attachment.handle.cropper.page'  , [ "column"=>$column['db_name'] , "navId"=>$navId , "menuId"=> $menuId, "bindId"=>$bindId ,'attachmentId'=> "__ATTACHMENT_ID__"]) }}";

        let btnName = "{{$column['db_name']}}";
        let uploadButton = KindEditor.uploadbutton({
            button : KindEditor("#upload_btn_{{$column['db_name']}}"),
            fieldName : 'uploader',
            url : "{{route('rotate.attachment.upload', ['client'=>'uploader'])}}",
            extraParams:{
                menuId : "{{$menuId}}" ,
                dbName : "{{$column['db_name']}}",
                bindId : "{{$bindId}}",
                _token : '{{csrf_token() }}'
            },
            afterUpload : function(data) {
                console.log(data)
                if (data.error === 0) {
                    $('#save_attachment_id_' + btnName ).val( data.body.id );
                    $('#preview_upload_file_' + btnName ).html(
                        '<a target="_blank" class="cutImageBtn" href="'+data.body.url +'" title="单击预览图片">' +
                        ''+data.body.url +'</a>'
                    );
                } else {
                    alert(data.message);
                }
            }
        });
        uploadButton.fileBox.change(function(e) {
            uploadButton.submit();
        });

        /**
         * 对上传图片进一步裁剪
         */
        $('#cut_btn_' + btnName ).click(function(){
            let attachmentId= $('#save_attachment_id_' + btnName).val();
            if(!attachmentId){
                alert("请上传图片");
                return ;
            }
            window.open(
                cropperHandleUrl.replace( "__ATTACHMENT_ID__", attachmentId ),
                '图片裁剪工具',[]);
        });

    });
</script>
