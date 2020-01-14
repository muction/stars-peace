@foreach( $datas as $key=>$item)
    <option value="{{$item['template_name']}}" @if( !$item['template_name'] ) disabled="disabled"  @endif
        @if($templateName == $item['template_name']) selected="selected" @endif
    >
        @for($i=0;$i< ($item['level']-1) *3;$i++) &nbsp; @endfor {{ $item['title'] }}
    </option>

    @if( isset($item['nodes']) )
        @component('StarsPeace::component.template-select-menu', ['datas'=> $item['nodes'] ,'templateName'=>$templateName])  @endcomponent
    @endif
 @endforeach
