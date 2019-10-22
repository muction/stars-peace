@extends("StarsPeace::iframe")


@section( 'car-head' )
    @include("StarsPeace::role.inc.inc")
@endsection

@section('car-body')
    <form class="form-horizontal" method="post" action="{{ route( 'rotate.role.storage' ,['infoId'=> isset($info['id']) ? $info['id'] : null ] ) }}">
        @csrf
        <input type="hidden" name="id" value="{{ old('id' ,isset($info['id'] ) ? $info['id'] : 0 )  }}">
        <div class="form-group  @error('title') has-error @enderror">
            <label for="title" class="col-sm-2 control-label">角色名称 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', isset($info['title'] ) ? $info['title'] : '' ) }}">
            </div>
        </div>
        <div class="form-group  @error('display_name') has-error @enderror">
            <label for="display_name" class="col-sm-2 control-label">显示名称 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name', isset($info['display_name'] ) ? $info['display_name'] : '' ) }}">
            </div>
        </div>

        <div class="form-group @error('description') has-error @enderror">
            <label for="textarea" class="col-sm-2 control-label">简单介绍 <span class="required">*</span></label>
            <div class="col-sm-10">
                <textarea class="form-control" id="textarea" name="description" rows="6">{{ old('description' ,  isset($info['description'] ) ? $info['description'] : '' ) }}</textarea>
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
@endsection