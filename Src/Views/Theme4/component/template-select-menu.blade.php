@foreach( $datas as $key=>$item)
    <li>
        <a href="{{ route('rotate.template.index' , ['nav'=>$validNavId ,'template_name'=>$item['template_name'] ]) }}">
            @for($i=0;$i< ($item['level']-1) *3;$i++) &nbsp; @endfor {{ $item['title'] }}
        </a>
    </li>

    @if( isset($item['nodes']) )
        @component('StarsPeace::component.template-select-menu', ['datas'=> $item['nodes'] ,'templateName'=>$templateName ,'validNavId'=>$validNavId])  @endcomponent
    @endif
 @endforeach
