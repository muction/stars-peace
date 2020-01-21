@foreach( $datas as $key=>$item)

        @if( isset($item['url']) )
            <a class="list-group-item" href="javascript:void(0)" data-url="{{ $item['url'] }}"  >
                @for($i=0;$i< ($item['level']-1) *3;$i++) &nbsp; @endfor
                {{ $item['title'] }}
            </a>
        @else
            <a class="list-group-item disabled" href="javascript:void(0)" title="未设置操作类">
                @for($i=0;$i< ($item['level']-1) *3;$i++) &nbsp; @endfor
                {{ $item['title'] }}
            </a>
        @endif
    @if( isset($item['nodes']) )
        @component('StarsPeace::component.content-sidebar', ['datas'=> $item['nodes'] ])  @endcomponent
    @endif
 @endforeach
