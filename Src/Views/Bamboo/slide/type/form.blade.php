@extends("StarsPeace::iframe")


@section( 'car-head' )
    @include("StarsPeace::slide.inc.inc")
@endsection

@section('car-body')

    <form class="form-horizontal" method="post" action="{{ route( 'rotate.slide.addTypePageStorage' , ['infoId'=> isset($info['id']) ? $info['id'] : 0 ] ) }}">
        @csrf
        <input type="hidden" name="id" value="{{ old('id' ,isset( $info['id'] ) ? $info['id'] : 0 )  }}">
        <div class="form-group  @error('title') has-error @enderror">
            <label for="title" class="col-sm-2 control-label">分类名称 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', isset($info['title'] ) ? $info['title'] : '' ) }}">
            </div>
        </div>
        <div class="form-group  @error('order') has-error @enderror">
            <label for="display_name" class="col-sm-2 control-label">排序 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="order" name="order" value="{{ old('order', isset($info['order'] ) ? $info['order'] : 10 ) }}">
            </div>
        </div>



        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">保存</button>
            </div>
        </div>
    </form>
@endsection