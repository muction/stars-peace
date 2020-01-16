@extends("StarsPeace::iframe")


@section( 'car-head' )
    @include("StarsPeace::user.inc.inc")
@endsection

@section('car-body')

    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route( 'rotate.user.storage' ,['infoId'=> isset($info['id']) ? $info['id'] : null  ,'isEditProfile'=>isset($isEditProfile) ? $isEditProfile : 0 ] ) }}">
        @csrf
        <input type="hidden" name="id" value="{{ old('id' ,isset($info['id'] ) ? $info['id'] : 0 )  }}">


        <div class="form-group  @error('username') has-error @enderror">
            <label for="username" class="col-sm-2 control-label">登录名 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" @if( isset($isEditProfile )) disabled="disabled" @endif class="form-control" id="username" name="username" value="{{ old('username', isset($info['username'] ) ? $info['username'] : '' ) }}">
            </div>
        </div>

        @if( isset($isEditProfile ))
            <div class="form-group  @error('origin_password') has-error @enderror">
                <label for="password" class="col-sm-2 control-label">原始密码 <span class="required">*</span></label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" id="origin_password" name="origin_password" value="{{ old('origin_password') }}">
                </div>
            </div>
        @endif
        <div class="form-group  @error('password') has-error @enderror">
            <label for="password" class="col-sm-2 control-label">密码 @if(!isset($info['id']))<span class="required">*</span>@endif </label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password" name="password" value="{{ old('password' ) }}">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">确认密码 @if(!isset($info['id']))<span class="required">*</span>@endif </label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation' ,isset($info['password_confirmation'] ) ? $info['password_confirmation'] : '') }}">
            </div>
        </div>

        <div class="form-group  @error('email') has-error @enderror">
            <label for="email" class="col-sm-2 control-label">邮箱 </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email" value="{{ old('email', isset($info['email'] ) ? $info['email'] : '' ) }}">
            </div>
        </div>

        <div class="form-group  @error('phone') has-error @enderror">
            <label for="phone" class="col-sm-2 control-label">手机 </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="phone" name="phone" maxlength="11" value="{{ old('phone', isset($info['phone'] ) ? $info['phone'] : '' ) }}">
            </div>
        </div>

        <div class="form-group  @error('branch') has-error @enderror">
            <label for="branch" class="col-sm-2 control-label">部门 </label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="branch" name="branch" value="{{ old('branch', isset($info['branch'] ) ? $info['branch'] : '' ) }}">
            </div>
        </div>

        <div class="form-group  @error('portrait') has-error @enderror">
            <label for="portrait" class="col-sm-2 control-label">头像</label>
            <div class="col-sm-10">
                 <input type="file" name="portrait" class="form-control">
                @if( isset( $info['portraitInfo']['save_file_path']) )
                    <div id="portraitPreviewDiv">
                        <input type="hidden" name="portrait_id" value="{{$info['portraitInfo']['id']}}">

                        <figure>
                            <img width="100" src="/storage/{{$info['portraitInfo']['save_file_path']}}/{{$info['portraitInfo']['save_file_name']}}" alt="图片一">
                            <a class="star-remove-portrait" href="javascript:void(0)">删除</a>
                        </figure>
                    </div>
                @endif
            </div>
        </div>

        @if( !isset($isEditProfile) )
            @include("StarsPeace::user.inc.basic")
        @endif

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
