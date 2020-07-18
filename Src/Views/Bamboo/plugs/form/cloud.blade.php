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
                            <a href="javascript:void(0)" class="_remove_queue_file{{ $column['db_name'] }}" data-file_id="{{ md5($__nowFileValue['id']) }}">移除</a>
                            {{ $__nowFileValue['original_name'] }}
                        </div>

                    @endforeach
                @endif
            </div>

            <div id="upload_result_{{$column['db_name']}}">
                @if( isset($column['now_value']) && $column['now_value'])
                    @foreach( $column['now_value'] as $__nowValue )
                        <input id="result_hidden_{{ md5($__nowValue['id']) }}" type="hidden" name="{{ $column['db_name'] }}[]" value="{{$__nowValue['id']}}">
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{asset('static/stars/cos-js-sdk/cos-js-sdk-v5.js')}}"></script>
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
                    // 建议返回服务器时间作为签名的开始时间，避免用户浏览器本地时间偏差过大导致签名错误
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
                    '<b></b></div>' ;
                $('#filelist_' + _uploaderModuleName).append( html ) ;

                return {
                    Bucket: tempUploadKey.bucket,
                    Region: tempUploadKey.region,
                    Key: tempUploadKey.uploadPath + f.name,
                    Body: f,
                };
            });
        });


        $("#pickfiles_" + _uploaderModuleName).click(function(){
            $('#selectFile_' + _uploaderModuleName).click();
        })

        $('#uploadfiles_' + _uploaderModuleName).click(function(){
            console.log(cos.getTaskList());
            cos.uploadFiles({
                files: fileQueue,
                SliceSize : 1024* 1024,
                onProgress:function (info) {
                    res  = eval('(' + result.response + ')');
                    if(res.error==0){
                        let html = '<input id="result_hidden_'+ file.id +'" type="hidden" name="'+_uploaderModuleName+'[]" value="'+ res.body.id +'">';
                        $('#upload_result_' + _uploaderModuleName ).append( html );
                    }
                },
                onFileFinish:function (err, data, options) {
                    // res  = eval('(' + result.response + ')');
                    // if(res.error==0){
                    //     let html = '<input id="result_hidden_'+ file.id +'" type="hidden" name="'+_uploaderModuleName+'[]" value="'+ res.body.id +'">';
                    //     $('#upload_result_' + _uploaderModuleName ).append( html );
                    // }
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
            console.log(fileId) //index_file_list_index_0
            $('#file_list_'+fileId).remove();
            $('#result_hidden_'+fileId).remove();
        }
    });
</script>

