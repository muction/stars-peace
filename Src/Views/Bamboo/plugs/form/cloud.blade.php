<div >
{{--  腾讯云存储  --}}
    @php( $__sheetUploadFieldOption = isset($column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_UPLOAD_CLOUD ]) ? $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_UPLOAD_CLOUD ] : [] )
    <div class="panel panel-default stars-plug">
        <div class="panel-heading">
            <input type="file" value="选择上传文件" multiple id="selectFile_{{$column['db_name']}}" style="display: none">

            <button type="button" class="btn btn-default btn-sm" id="pickfiles_{{$column['db_name']}}" href="javascript:;">
                选择文件
            </button>
            <button type="button" class="btn btn-default btn-sm" id="uploadfiles_{{$column['db_name']}}" href="javascript:;">
                开始上传
            </button>
            <span id="progress_show_{{$column['db_name']}}"></span>
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

                        <div id="file_list_index_{{ md5($__nowFileValue['id']) }}">
                            <a href="javascript:void(0)" class="_remove_queue_file{{ $column['db_name'] }}" data-file_id="index_{{ md5($__nowFileValue['id']) }}">移除</a>
                            {{ $__nowFileValue['original_name'] }}
                        </div>

                    @endforeach
                @endif
            </div>

            <div id="upload_result_{{$column['db_name']}}">
                @if( isset($column['now_value']) && $column['now_value'])
                    @foreach( $column['now_value'] as $__nowValue )
                        <input id="result_hidden_index_{{ md5($__nowValue['id']) }}" type="hidden" name="{{ $column['db_name'] }}[]" value="{{$__nowValue['id']}}">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('static/stars/cos-js-sdk/cos-js-sdk-v5.js')}}"></script>
<script type="text/javascript" src="{{asset('static/stars/js/jquery.md5.js')}}"></script>
<script type="text/javascript">
    let _uploaderModuleName ="{{$column['db_name']}}";
    var tempUploadKey = @json($__sheetUploadFieldOption['tmpUploadKey']);
    $(function () {
        credentials = tempUploadKey.credentials;
        var cos = new COS({
            getAuthorization: function (options, callback) {
                callback({
                    TmpSecretId: credentials.tmpSecretId,
                    TmpSecretKey: credentials.tmpSecretKey,
                    XCosSecurityToken: credentials.sessionToken,
                    StartTime: tempUploadKey.startTime, // 时间戳，单位秒，如：1580000000
                    ExpiredTime: tempUploadKey.expiredTime, // 时间戳，单位秒，如：1580000900
                });
            }
        });
        var fileQueue = null;
        $('#selectFile_' + _uploaderModuleName).on("change",function(e){
            let files = e.target.files;
            fileQueue = [].map.call(files, function (f,index) {
                fileId = "index_" + index;
                index = "file_list_index_" + index;
                let html = '<div id="' + index + '">' +
                    '<a href="javascript:void(0)" class="_remove_queue_file'+_uploaderModuleName+'" data-file_id="'+fileId+'' +
                    '">移除</a>' +
                    ' ' + f.name + ' (' + f.size + ') ' +
                    '<b id="process_'+fileId+'"></b></div>' ;
                $('#filelist_' + _uploaderModuleName).append( html ) ;
                var spl = f.name.split(".");
                var fileType= spl[spl.length-1];
                return {
                    Bucket: tempUploadKey.bucket,
                    Region: tempUploadKey.region,
                    Key: tempUploadKey.uploadPath + $.md5(f.name) + '.' + fileType,
                    Body: f
                };
            });
        });


        $("#pickfiles_" + _uploaderModuleName).click(function(){
            $('#selectFile_' + _uploaderModuleName).click();
        })

        $('#uploadfiles_' + _uploaderModuleName).click(function(){
            cos.uploadFiles({
                files: fileQueue,
                SliceSize : 1024* 1024,
                onTaskReady: function(taskId){
                    console.log(taskId)
                },
                onProgress:function (info) {
                    var percent = parseInt(info.percent * 10000) / 100;
                    var speed = parseInt(info.speed / 1024 / 1024 * 100) / 100;
                    $('#progress_show_' + _uploaderModuleName).html('进度：' + percent + '%; 速度：' + speed + 'Mb/s;');
                },
                onFileFinish:function (err, data, options) {
                    console.log('===',options ,data, '===')
                    options['_token'] = "{{ csrf_token() }}";
                    options['bind_id'] = "{{$bindId}}"
                    $.ajax({
                        url : "{{route('rotate.attachment.upload.clud', ['client'=>'cloud'])}}" ,
                        type : "post",
                        dataType:"json",
                        data: options,
                        error:function(err){
                            console.log("请求保存时出错", err)
                        },
                        success:function(data){
                            if(data.error == 0){
                                data = data.body;
                                let html = '<input id="result_hidden_index_'+ options.Index +'" type="hidden" name="'+_uploaderModuleName+'[]" value="'+ data.id +'">';
                                $('#upload_result_' + _uploaderModuleName ).append( html );
                            }else{
                                console.log(data,'获取存储ID时错误');
                            }
                        }
                    })
                }
            },function(err, data){
                console.log(err || data);
            });
        });
    });

    //移除队列
    $(document.body).on('click', '._remove_queue_file'+_uploaderModuleName, function(){
        if(window.confirm('确定要移除队列吗?')){
            let fileId = $(this).data('file_id');
            $('#file_list_'+fileId).remove();
            $('#result_hidden_'+fileId).remove();
        }
    });
</script>

