<div >
    @php( $__sheetUploadFieldOption = isset($column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_UPLOAD ]) ? $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_UPLOAD ] : [] )
    <div class="panel panel-default stars-plug">
        <div class="panel-heading">
            <button type="button" class="btn btn-default btn-sm" id="pickfiles_{{$column['db_name']}}" href="javascript:;">
                选择文件
            </button>
            <button type="button" class="btn btn-default btn-sm" id="uploadfiles_{{$column['db_name']}}" href="javascript:;">
                开始上传
            </button>
        </div>
        <div class="panel-body">
            <div>
                <p class="text-muted">
                    支持文件大小: {{ isset($__sheetUploadFieldOption['maxSize']) ? $__sheetUploadFieldOption['maxSize'].'MB' : '未配置' }}
                </p>

                <p class="text-muted">
                    支持文件格式: {{  isset($__sheetUploadFieldOption['fileType']) ? implode(', ' , $__sheetUploadFieldOption['fileType']) : '未配置'  }}
                </p>
            </div>

            <div id="filelist_{{$column['db_name']}}">
                @if( isset($column['now_value']) && $column['now_value'])
                    @foreach( $column['now_value'] as $__nowFileValue )

                        <div id="{{ md5($__nowFileValue['id']) }}">
                            <a href="javascript:void(0)" class="_remove_queue_fileuploader" data-file_id="{{ md5($__nowFileValue['id']) }}">移除</a>
                            {{ $__nowFileValue['original_name'] }}
                        </div>

                    @endforeach
                @endif
            </div>


            <div id="upload_result_{{$column['db_name']}}">
                @if( isset($column['now_value']) && $column['now_value'])
                    @foreach( $column['now_value'] as $__nowValue )
                        <input id="result_hidden_{{ md5($__nowValue['id']) }}" type="hidden" name="uploader[]" value="{{$__nowValue['id']}}">
                    @endforeach
                @endif
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">

    $(function () {
        let _uploaderModuleName ="{{$column['db_name']}}";
        let uploader = new plupload.Uploader({
            runtimes    : 'html5,flash,silverlight,html4',
            browse_button: "pickfiles_{{$column['db_name']}}",
            url:    "{{route('rotate.attachment.upload', ['client'=>'uploader'])}}" ,
            flash_swf_url: "{{asset("static/stars/plugs/plupload/js/Moxie.swf")}}",
            silverlight_xap_url: "{{asset("static/stars/plugs/plupload/js/Moxie.xap")}}",
            file_data_name : 'uploader',
            filters: {
                 max_file_size: '{{ isset($__sheetUploadFieldOption['maxSize']) ? $__sheetUploadFieldOption['maxSize'].'MB' : 0 }}',
                 mime_types: [
                     {title : "files", extensions : "{{  isset($__sheetUploadFieldOption['fileType']) ? implode(',' , $__sheetUploadFieldOption['fileType']) : '*'  }}"},
                 ]
             },

            init: {
                PostInit: function (up) { },
                BeforeUpload: function(up, file) {
                    //设置参数
                    uploader.setOption("multipart_params", {
                        menuId    : "{{$menuId}}",
                        bindId : "{{$bindId}}",
                        dbName : _uploaderModuleName ,
                        _token : "{{csrf_token()}}"
                    });
                },

                FilesAdded: function (up, files) {

                    console.log(files)
                    plupload.each(files, function (file) {
                        let html = '<div id="' + file.id + '">' +
                            '<a href="javascript:void(0)" class="_remove_queue_file'+_uploaderModuleName+'" data-file_id="'+file.id+'' +
                            '">移除</a>' +
                            ' ' + file.name + ' (' + plupload.formatSize(file.size) + ') ' +
                            '<b></b></div>' ;
                        $('#filelist_' + _uploaderModuleName).append( html ) ;
                    });
                },

                UploadProgress: function (up, file) {
                    document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                },

                Error: function (up, err) {
                    if( err.code==-600 ){
                        alert("选取文件不合法，请仔细检查文件名，文件大小。");
                    }else{
                        console.log("\nError #" + err.code + ": " + err.message);
                    }

                },

                FileUploaded:function(up ,file ,result){
                    res  = eval('(' + result.response + ')');
                    if(res.error==0){
                        let html = '<input id="result_hidden_'+ file.id +'" type="hidden" name="'+_uploaderModuleName+'[]" value="'+ res.body.id +'">';
                        $('#upload_result_' + _uploaderModuleName ).append( html );
                    }

                },
                UploadComplete:function(up, files){
                },
                QueueChanged:function(up ){
                }
            }
        });
        uploader.init();

        $(document.body).on('click', '._remove_queue_file'+_uploaderModuleName, function(){
            if(window.confirm('确定要移除队列吗?')){
                let fileId = $(this).data('file_id');
                uploader.removeFile(fileId);
                $('#'+fileId).remove();
                $('#result_hidden_'+fileId).remove();
            }
        });

        $('#uploadfiles_' + _uploaderModuleName).click(function(){
            if(uploader.total.queued ==0){
                alert("请选择文件");
                return ;
            }
            uploader.start();
        });
    });

</script>

