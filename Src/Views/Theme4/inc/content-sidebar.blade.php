<ul class="nav nav-drawer">

    @component( "StarsPeace::component.article-sidebar" , ['sides'=>$sidebar] ) @endcomponent

    @if( isset($articleNav) && $articleNav && \Stars\Rbac\Entity\UserEntity::can( 'rotate.content.access' ) ||  \Stars\Rbac\Entity\UserEntity::hasRole( 'root') )
        <li class="nav-item nav-item-has-subnav article-sidebar">
            <a href="javascript:void(0)">
                <i class="mdi mdi-bookmark"></i>
                <span>内容管理</span>
            </a>
            <ul class="nav nav-subnav">

                @if( config('stars.ppt') )
                <li>
                    <a class="" target="request-content" href="{{ route(   'rotate.slide.index.type'  ) }}">
                        幻灯片
                    </a>
                </li>
                @endif

                @foreach($articleNav as $_nav)
                    <li>
                        <a class="" target="request-content" href="{{ route('rotate.article.articles', ['navId'=> $_nav['id']]) }}">
                            {{ $_nav['title'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endif
</ul>

