@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::nav.menu.inc")
@endsection

@section('car-body')
    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route( 'rotate.menu.add.storage' ,['navId'=> $nav['id'] ] ) }}">
        @csrf
        <input type="hidden" name="id" value="{{ old('id' ,isset($info['id'] ) ? $info['id'] : 0 )  }}">

        <div class="form-group">
            <label for="parent_id" class="col-sm-2 control-label">父级</label>
            <div class="col-sm-10">
                <select class="form-control" id="parent_id" name="parent_id" >
                    <option value="0">--顶级--</option>
                    @component( "StarsPeace::component.menuoption" , ['datas'=> $tree ,'selected'=> isset($info['parent_id']) ? $info['parent_id'] :'' ] ) @endcomponent
                </select>
            </div>
        </div>

        <div class="form-group  @error('title') has-error @enderror">
            <label for="title" class="col-sm-2 control-label">菜单名称 <span class="required">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', isset($info['title'] ) ? $info['title'] : '' ) }}">
            </div>
        </div>

        <div class="form-group  @error('image') has-error @enderror">
            <label for="portrait" class="col-sm-2 control-label">导航图片</label>
            <div class="col-sm-10">
                <input type="file" name="image" class="form-control">
                @if( isset( $info['image']['save_file_path']) )
                    <div id="portraitPreviewDiv">
                        <input type="hidden" name="image_id" value="{{$info['image_id']}}">

                        <figure>
                            <img width="100" src="/storage/{{$info['image']['save_file_path']}}/{{$info['image']['save_file_name']}}" alt="图片一">
                            <a class="star-remove-portrait" href="javascript:void(0)">删除</a>
                        </figure>
                    </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="route_name" class="col-sm-2 control-label">路由名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="route_name" name="route_name" value="{{ old('route_name', isset($info['route_name'] ) ? $info['route_name'] : '' ) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="href" class="col-sm-2 control-label">外链地址</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="href" name="href" value="{{ old('href', isset($info['href'] ) ? $info['href'] : '' ) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="icon" class="col-sm-2 control-label">ICON名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', isset($info['icon'] ) ? $info['icon'] : '' ) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="order" class="col-sm-2 control-label">排序</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="order" name="order" value="{{ old('order', isset($info['order'] ) ? $info['order'] : 10 ) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="template_name" class="col-sm-2 control-label">模板选择</label>
            <div class="col-sm-10">
                <select class="form-control" id="template_name" name="template_name" >
                    <option value="">--请选择--</option>
                    @foreach($themeFiles as $file)
                        <option value="{{ $file }}" @if( isset($info['template_name']) && $info['template_name']==$file ) selected="selected" @endif>
                            {{ $file }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="template_name" class="col-sm-2 control-label">模板类型</label>
            <div class="col-sm-10">
                <div class="example-box">
                    <label class="lyear-radio radio-inline radio-primary">
                        <input type="radio" name="template_type" value="page"
                               @if( old('template_type' , isset($info['template_type']) ? $info['template_type'] : 'page' ) == 'page'  ) checked="checked" @endif >
                        <span>单页</span>
                    </label>
                    <label class="lyear-radio radio-inline radio-primary">
                        <input type="radio" name="template_type" value="list"
                               @if( old('template_type' , isset($info['template_type']) ? $info['template_type'] : '' ) == 'list'  ) checked="checked" @endif
                        >
                        <span>列表</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="icon" class="col-sm-2 control-label">SEO名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ old('seo_title', isset($info['seo_title'] ) ? $info['seo_title'] : '' ) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="icon" class="col-sm-2 control-label">SEO关键字</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" value="{{ old('seo_keywords', isset($info['seo_keywords'] ) ? $info['seo_keywords'] : '' ) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="icon" class="col-sm-2 control-label">SEO介绍</label>
            <div class="col-sm-10">
                <textarea class="form-control" id="seo_description" name="seo_description" >{{ old('seo_description', isset($info['seo_description'] ) ? $info['seo_description'] : '' ) }}</textarea>
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
