
@foreach($sides as $side)

    @if( !isset($side['nodes']) || !$side['nodes'] )
        @if( isset($children) )
            <li>
                <a target="request-content" data-id="1" @if(!isset($side['url'])) style="text-decoration: underline; cursor: not-allowed;" class="disabled" @endif href="{{ isset($side['url']) ? $side['url'] : 'javascript:void(0)'}}">
                    {{ $side['title'] }}
                </a>
            </li>
        @elseif( \Stars\Rbac\Entity\UserEntity::can( $side['route_name']) ||  \Stars\Rbac\Entity\UserEntity::hasRole( 'root') )
            <li class="nav-item">
                <a target="request-content" data-id="2" @if(!isset($side['url'])) style="text-decoration: underline; cursor: not-allowed;" class="disabled" @endif href="{{ isset($side['url']) ? $side['url'] : 'javascript:void(0)'}}" >
                    <i class="{{ $side['icon'] }}"></i>
                    <span>{{ $side['title'] }}</span>
                </a>
            </li>
        @endif

    @else

        @if( \Stars\Rbac\Entity\UserEntity::can( $side['route_name']) ||  \Stars\Rbac\Entity\UserEntity::hasRole( 'root') )
            <li class="nav-item nav-item-has-subnav" >
                <a href="{{ isset($side['url']) ? $side['url'] : 'javascript:void(0)'}}"
                   target="request-content"
                   @if(!isset($side['url'])) title="未绑定可操作项" style="text-decoration: underline; cursor: not-allowed;" @endif
                   data-id="3"
                >
                    <i class="{{ $side['icon'] }}"></i>

                    <span >{{ $side['title'] }}</span>

                </a>
                <ul class="nav nav-subnav">
                    @component( "StarsPeace::component.article-sidebar" ,['sides'=>$side['nodes'] ,'children'=>time()  ] ) @endcomponent
                </ul>
            </li>
        @endif
    @endif

@endforeach


