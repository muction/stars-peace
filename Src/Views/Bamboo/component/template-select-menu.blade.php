@foreach( $datas as $key=>$item)
    <option value="{{ $item['template_name'] }}"
        @if($templateName == $item['template_name'])
            selected="selected"
            @endif
    >
        @for($i=0;$i< ($item['level']-1) *3;$i++) &nbsp; @endfor {{ $item['title'] }}
    </option>

    @if( isset($item['nodes']) )
        @component('StarsPeace::component.template-select-menu', ['datas'=> $item['nodes'] ,'templateName'=>$templateName ,'validNavId'=>$validNavId])  @endcomponent
    @endif
 @endforeach
