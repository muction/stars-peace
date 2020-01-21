<ul class="nav nav-drawer">

    @foreach($articleSides as $nav)
        <li class="nav-item nav-item-has-subnav article-sidebar">
            <a href="javascript:void(0)">
                <i class="mdi mdi-bookmark"></i>
                <span>{{ $nav['title'] }}内容管理</span>
            </a>
            <ul class="nav nav-subnav">
                @component( "StarsPeace::component.article-sidebar" , ['sides'=>$nav['menus']] ) @endcomponent
            </ul>
        </li>
    @endforeach
</ul>

