@extends("StarsPeace::iframe")


@section( 'car-head' )
    @include("StarsPeace::role.inc.inc")
@endsection

@section('car-body')

        <h4> 角色名称 : {{$role['title']}} &nbsp; <button type="submit" class="btn btn-default btn-sm" id="save-btn">保存</button> </h4>

        <div style="width: 100%;  ">
            @foreach( $allNavMenus as $index=>$navInfo )
                <div class="card" >
                    <div class="card-header">
                        <h4>
                            {{ $navInfo['nav']['name'] }}
                        </h4>
                    </div>
                    <div class="card-body">
                        <ul id="ztree{{$index}}" class="ztree"></ul>
                    </div>
                </div>
            @endforeach
        </div>

    @include("StarsPeace::role.inc.bind")
@endsection
