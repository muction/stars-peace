@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::nav.menu.inc")
@endsection

@section('car-body')
    <form class="form-horizontal" method="post" action="{{ route( 'rotate.nav.storage' ) }}">
        @csrf
        <input type="hidden" name="id" value="{{ old('id' ,isset($info['id'] ) ? $info['id'] : 0 )  }}">
        <div class="form-group  @error('title') has-error @enderror">
            <label for="title" class="col-sm-2 control-label">导航名称 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', isset($info['title'] ) ? $info['title'] : '' ) }}">
            </div>
        </div>
        <div class="form-group  @error('theme') has-error @enderror">
            <label for="title" class="col-sm-2 control-label">主题名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="theme" name="theme" value="{{ old('theme', isset($info['theme'] ) ? $info['theme'] : '' ) }}">
            </div>
        </div>
        <div class="form-group @error('remark') has-error @enderror">
            <label for="textarea" class="col-sm-2 control-label">简单介绍 <span class="required">*</span></label>
            <div class="col-sm-10">
                <textarea class="form-control" id="textarea" name="remark" rows="6">{{ old('remark' ,  isset($info['remark'] ) ? $info['remark'] : '' ) }}</textarea>
            </div>
        </div>

        <div class="form-group @error('article') has-error @enderror">
            <label for="textarea" class="col-sm-2 control-label">文章管理 <span class="required">*</span></label>
            <div class="col-sm-10">
                <label class="lyear-radio radio-inline radio-primary">
                    <input type="radio" name="article" value="1" @if(isset($info['article']) && $info['article'] == 1) checked="checked" @endif ><span>是</span>
                </label>
                <label class="lyear-radio radio-inline radio-primary">
                    <input type="radio" name="article" value="0" @if(isset($info['article']) && $info['article'] == 0 || !isset($info)) checked="checked" @endif ><span>否</span>
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
