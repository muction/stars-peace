
@foreach($sides as $side)

    @if( !isset($side['nodes']) || !$side['nodes'] )
        @if( isset($children) )
            <li>
                <a class="multitabs allow-close-sidebar" target="request-content" href="{{ isset($side['url']) ? $side['url'] : ''}}">
                    {{ $side['title'] }}
                </a>
            </li>
        @elseif( \Stars\Rbac\Entity\UserEntity::can( $side['route_name']) ||  \Stars\Rbac\Entity\UserEntity::hasRole( 'root') )
            <li class="nav-item">
                <a class="multitabs allow-close-sidebar" target="request-content" href="{{ isset($side['url']) ? $side['url'] : ''}}" >
                    <i class="{{ $side['icon'] }}"></i>
                    <span>{{ $side['title'] }}</span>
                </a>
            </li>
        @endif

    @else

        @if( \Stars\Rbac\Entity\UserEntity::can( $side['route_name']) ||  \Stars\Rbac\Entity\UserEntity::hasRole( 'root') )
            <li class="nav-item nav-item-has-subnav">
                <a href="{{ isset($side['url']) ? $side['url'] : ''}}" target="request-content">
                    <i class="{{ $side['icon'] }}"></i>
                    <span>{{ $side['title'] }}</span>
                </a>
                <ul class="nav nav-subnav">
                    @component( "StarsPeace::component.article-sidebar" ,['sides'=>$side['nodes'] ,'children'=>time()  ] ) @endcomponent
                </ul>
            </li>
        @endif
    @endif

@endforeach


