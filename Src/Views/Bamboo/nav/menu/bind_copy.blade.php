@extends("StarsPeace::iframe")

@section( 'car-head' )
    @include("StarsPeace::nav.menu.inc")
@endsection

@section("car-body")
    <style type="text/css">

        .btn-start{
            text-align: center;
        }

    </style>
    <ul class="nav-step step-dots">
        <li class="nav-step-item @if($setUp>=2) complete @else active @endif ">
            <span>
                <select class="form-control target-nav" style="height: 28px;font-size:12px" @if( $setUp !=0 ) disabled="disabled" @endif >
                    <option value="0">--选择导航--</option>
                    @foreach($navs as $nn)
                        @if( $nav['id'] != $nn['id'] && $nn['id'] !=1 )
                        <option value="{{$nn['id']}}" @if($targetNavId== $nn['id'] ) selected="selected" @endif >
                             {{$nn['title']}}
                        </option>
                        @endif
                    @endforeach
                </select>
            </span>
            <a class="active"href="{{ route( 'rotate.nav.menu.copy' , ['navId'=>$nav['id'] ]) }}" ></a>
        </li>

        <li class="nav-step-item @if($setUp==3) complete @else active @endif ">
            <span>复制</span>
            <a href="#"></a>
        </li>

        <li class="nav-step-item @if($setUp==3) complete @else active @endif ">
            <span>完成</span>
            <a href="#"></a>
        </li>

    </ul>

    <div class="btn-start">
        <button type="button" @if($setUp ==2 ) disabled="disabled" @endif class="btn btn-default start-copy">{{ $setUp==2 ? '复制中...' : '开始复制'  }}</button>
    </div>


    <script>
        var _targetCopyUrl = "{{route('rotate.nav.menu.copy' , ['navId'=>$nav['id'] ,'targetNavId'=> '__TARGET_NAV_ID__' ,'setUp'=>2 ])}}";
        $(function(){
            $('.start-copy').click(function(){
                let targetNavId = parseInt( $('.target-nav').val() );
                if(!targetNavId){
                    alert('请选择目标导航');
                    return false;
                }

                if( window.confirm( "是否开始复制菜单信息？" )){
                    window.location.href = _targetCopyUrl.replace('__TARGET_NAV_ID__' , targetNavId) ;
                }
            });
        });
    </script>
@endsection