
@foreach($sides as $side)

    @if( !isset($side['nodes']) || !$side['nodes'] )
        @if( isset($children) )
            <li>
                <a class="multitabs" style="margin-left: 15px" href="{{route( 'rotate.article.articles' , ['navId'=>$side['nav_id'] ,'menuId'=>$side['id']] )}}">
                    {{ $side['title'] }}
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="multitabs" href="{{route( 'rotate.article.articles' , ['navId'=>$side['nav_id'] ,'menuId'=>$side['id']] )}}">
                    <i class="{{ $side['icon'] }}"></i>
                    <span>{{ $side['title'] }}</span>
                </a>
            </li>
        @endif

    @else
        <li class="nav-item nav-item-has-subnav">
            <a href="javascript:void(0)">
                <i class="{{ $side['icon'] }}"></i>
                <span>{{ $side['title'] }}</span>
            </a>
            <ul class="nav nav-subnav">
                @component( "StarsPeace::component.article.sidebar" ,['sides'=>$side['nodes'] ,'children'=>time()  ] ) @endcomponent
            </ul>
        </li>
    @endif
@endforeach
