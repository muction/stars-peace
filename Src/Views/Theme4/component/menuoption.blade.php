@foreach( $datas as $key=>$item)
    <option value="{{$item['id']}}" @if( isset($selected) && $selected==$item['id'] ) selected="selected" @endif>
        @for($i=0;$i< ($item['level']-1) *3;$i++) &nbsp; @endfor
        {{ $item['title'] }}
    </option>
    @if( isset($item['nodes']) )
        @component('StarsPeace::component.menuoption', ['datas'=> $item['nodes'] ,'selected'=>isset($selected) ? $selected : '' ])  @endcomponent
    @endif
@endforeach
