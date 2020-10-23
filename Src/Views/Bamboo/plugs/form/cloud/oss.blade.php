
<script type="text/javascript" src="{{asset('static/stars/oss-js-sdk/oss-sdk.js')}}"></script>
<script type="text/javascript" src="{{asset('static/stars/js/jquery.md5.js')}}"></script>
<script type="text/javascript">
    let _uploaderModuleName ="{{$column['db_name']}}";
    var tempUploadKey = @json($__sheetUploadFieldOption['tmpUploadKey']);
    $(function () {
        credentials = tempUploadKey.credentials;

        let oss = new OSS({
            region: tempUploadKey.region,
            //云账号AccessKey有所有API访问权限，建议遵循阿里云安全最佳实践，创建并使用STS方式来进行API访问
            accessKeyId: credentials.tmpSecretId,
            accessKeySecret: credentials.tmpSecretKey,
            stsToken:  credentials.sessionToken,
            bucket: tempUploadKey.bucket
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
            oss.uploadFiles({
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