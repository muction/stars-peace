<ul class="nav nav-tabs">



    @foreach( $menuBindInfo as $bindInfo )

        <li @if( $bindId == $bindInfo['id'] && !$action ) class="active" @endif >
            <a href="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindInfo['id']]) }}">
                {{ $bindInfo['title'] }}
            </a>
        </li>

        @if( $bindId == $bindInfo['id'] )
            <li @if( $action == 'add' ) class="active" @endif >
                <a href="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindInfo['id'] ,'action'=>'add' ]) }}">
                    <i class="mdi mdi-pen"></i>新增
                </a>
            </li>
        @endif
    @endforeach


</ul>

