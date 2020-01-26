@extends("StarsPeace::iframe")


@section( 'car-head' )
    @include("StarsPeace::slide.inc.inc")
@endsection

@section('car-body')
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route( 'rotate.slide.addPageStorage' ,[ 'typeId'=> isset($typeInfo['id']) ? $typeInfo['id'] : null , 'infoId'=>isset($info['id']) ? $info['id'] : 0 ] ) }}">
        @csrf
        <input type="hidden" name="id" value="{{ old('id' ,isset($info['id'] ) ? $info['id'] : 0 )  }}">

        <div class="form-group  @error('title') has-error @enderror">
            <label for="title" class="col-sm-2 control-label">名称 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', isset($info['title'] ) ? $info['title'] : '' ) }}">
            </div>
        </div>

        <div class="form-group  @error('image') has-error @enderror">
            <label for="portrait" class="col-sm-2 control-label">图片</label>
            <div class="col-sm-10">
                <input type="file" name="image" class="form-control">
                @if( isset( $info['attachment']['save_file_path']) )
                    <div id="portraitPreviewDiv">
                        <input type="hidden" name="attachment_id" value="{{$info['attachment_id']}}">

                        <figure>
                            <img width="100" src="/storage/{{ $info['attachment']['save_file_path'] }}/{{$info['attachment']['save_file_name']}}" alt="图片一">
                            <a class="star-remove-portrait" href="javascript:void(0)">删除</a>
                        </figure>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">外链地址</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="url" name="url" value="{{ old('url', isset($info['url'] ) ? $info['url'] : '' ) }}">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">简单介绍</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="summary" name="summary" rows="6">{{ old('summary' ,  isset($info['summary'] ) ? $info['summary'] : '' ) }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">排序</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="order" name="order" value="{{ old('order', isset($info['order'] ) ? $info['order'] : 10 ) }}">
            </div>
        </div>

        <div class="form-group @error('status') has-error @enderror">
            <label for="textarea" class="col-sm-2 control-label">状态 <span class="required">*</span></label>
            <div class="col-sm-10">
                <label class="lyear-radio radio-inline radio-primary">
                    <input type="radio" name="status" value="1" checked="checked"><span>启用</span>
                </label>
                <label class="lyear-radio radio-inline radio-primary">
                    <input type="radio" name="status" value="0" ><span>关闭</span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">保存</button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(function(){
            $('.star-remove-portrait').click(function(){
                if(confirm("确定删除吗?")){
                    $('#portraitPreviewDiv').remove();
                }
            });
        });
    </script>
@endsection
