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

@php($__cloudPlatform = isset($__sheetUploadFieldOption['tmpUploadKey']['platform']) ?$__sheetUploadFieldOption['tmpUploadKey']['platform']: '' )
@if($__cloudPlatform == 'OSS')
    @include("StarsPeace::plugs.form.cloud.oss")
@elseif($__cloudPlatform == 'COS')
    @include("StarsPeace::plugs.form.cloud.cos")
@endif
