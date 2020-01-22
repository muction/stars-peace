@extends("StarsPeace::iframe")


@section( 'car-head' )
    @include("StarsPeace::permission.inc.inc")
@endsection

@section('car-body')

    <form class="form-horizontal" method="post" action="{{ route( 'rotate.permission.addTypePageStorage' , ['infoId'=> isset($permissionType['id']) ? $permissionType['id'] : 0 ] ) }}">
        @csrf
        <input type="hidden" name="id" value="{{ old('id' ,isset( $permissionType['id'] ) ? $permissionType['id'] : 0 )  }}">
        <div class="form-group  @error('title') has-error @enderror">
            <label for="title" class="col-sm-2 control-label">分类名称 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', isset($permissionType['title'] ) ? $permissionType['title'] : '' ) }}">
            </div>
        </div>
        <div class="form-group  @error('order') has-error @enderror">
            <label for="display_name" class="col-sm-2 control-label">排序 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="order" name="order" value="{{ old('order', isset($permissionType['order'] ) ? $permissionType['order'] : 10 ) }}">
            </div>
        </div>



        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">保存</button>
            </div>
        </div>
    </form>
@endsection