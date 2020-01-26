<ul class="nav nav-drawer">
    @component( "StarsPeace::component.sidebar" , ['sides'=>$sidebar] ) @endcomponent

    @if( \Stars\Rbac\Entity\UserEntity::can('rotate.article.articles') ||  \Stars\Rbac\Entity\UserEntity::hasRole( 'root') )
        @foreach($articleSides as $nav)
            <li class="nav-item nav-item-has-subnav">
                <a href="javascript:void(0)">
                    <i class="mdi mdi-bookmark"></i>
                    <span>{{ $nav['title'] }}内容管理</span>
                </a>
                <ul class="nav nav-subnav">
                    @component( "StarsPeace::component.article-sidebar" , ['sides'=>$nav['menus']] ) @endcomponent
                </ul>
            </li>
        @endforeach
    @endif
</ul>

