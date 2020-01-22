
@php( $_routeName = Request()->route()->getName() )

<ul id="myTabs" class="nav nav-tabs" role="tablist">



    <li  @if( $_routeName == 'rotate.slide.index.type') class="active" @endif>
        <a href="{{ route( 'rotate.slide.index.type' ) }}" >分类列表</a>
    </li>
    <li  @if( $_routeName == 'rotate.slide.addTypePage') class="active" @endif>
        <a href="{{ route( 'rotate.slide.addTypePage' ) }}" >新增分类</a>
    </li>

   @if( in_array($_routeName, ['rotate.slide.index', 'rotate.slide.addPage', 'rotate.slide.editPage' ]  ))
        <li @if( $_routeName == 'rotate.slide.index') class="active" @endif >
            <a href="{{ route('rotate.slide.index' , ['typeId'=>$typeInfo['id'] ]) }}" >幻灯片列表</a>
        </li>

        <li  @if( $_routeName == 'rotate.slide.addPage') class="active" @endif>
            <a href="{{ route('rotate.slide.addPage' , ['typeId'=>$typeInfo['id'] ] ) }}" >新增幻灯片</a>
        </li>

        @if( isset( $info['id']) )
            <li  @if( $_routeName == 'rotate.slide.editPage') class="active" @endif>
                <a href="{{ route('rotate.slide.editPage' , [ 'typeId'=>$info['slide_type_id'] ,'infoId'=>$info['id']]) }}" >编辑幻灯片</a>
            </li>
        @endif

    @endif


    @if( in_array( $_routeName, ['rotate.slide.editTypePage' ]) )
        <li  @if( $_routeName =='rotate.slide.editTypePage' ) class="active" @endif>
            <a href="{{ route( 'rotate.slide.editTypePage' , ['infoId'=>$info['id']]) }}" >编辑分类</a>
        </li>
    @endif


</ul>