<ul class="nav nav-drawer">
    @if( isset($articleNav) && $articleNav && \Stars\Rbac\Entity\UserEntity::can( 'rotate.content.access' ) ||  \Stars\Rbac\Entity\UserEntity::hasRole( 'root') )
        <li class="nav-item nav-item-has-subnav article-sidebar">
            <a href="javascript:void(0)">
                <i class="mdi mdi-bookmark"></i>
                <span>内容管理</span>
            </a>
            <ul class="nav nav-subnav">
                @component( "StarsPeace::component.article-sidebar" , ['sides'=>$articleSides] ) @endcomponent
            </ul>
        </li>
    @endif
</ul>

