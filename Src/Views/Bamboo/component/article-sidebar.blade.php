
@foreach($sides as $side)

    @if(  $hasSuppersRole|| in_array($side['id'], $roleNavMenus) )

        @if( !isset($side['nodes']) || !$side['nodes'] )
            @if( isset($children) )
                <li>
                    <a target="request-content"
                       @if(!isset($side['url']))
                            style="text-decoration: underline; cursor: not-allowed;" class="disabled"
                       @else
                            href="{{ $side['url'] }}"
                       @endif

                       title="{{ $side['title'] }}"
                    >
                        {{ $side['title'] }}
                    </a>
                </li>
            @else
                <li class="nav-item">
                    <a target="request-content"
                       @if(!isset($side['url']))
                            style="text-decoration: underline; cursor: not-allowed;" class="disabled"
                       @else
                            href="{{ $side['url'] }}"
                       @endif
                       title="{{ $side['title'] }}"
                    >
                        <i class="{{ $side['icon'] }}"></i>
                        <span>{{ $side['title'] }}</span>
                    </a>
                </li>
            @endif

        @else
            <li class="nav-item nav-item-has-subnav" >
                <a
                   target="request-content"
                   @if(!isset($side['url']))
                        title="未绑定可操作项" style="text-decoration: underline; cursor: not-allowed;"
                   @else
                        href="{{ $side['url'] }}"
                        title="{{ $side['title'] }}"
                   @endif
                >
                    <i class="{{ $side['icon'] }}"></i>

                    <span >{{ $side['title'] }}</span>

                </a>
                <ul class="nav nav-subnav">
                    @component( "StarsPeace::component.article-sidebar" ,['sides'=>$side['nodes'] ,'children'=>time() ,'roleNavMenus'=>$roleNavMenus ,'hasSuppersRole'=>$hasSuppersRole ] ) @endcomponent
                </ul>
            </li>
        @endif
    @endif
@endforeach


