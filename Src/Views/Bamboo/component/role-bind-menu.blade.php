@foreach( $datas as $key=>$item)
    @if( isset( $item['id'] ) )
        <label class="lyear-checkbox checkbox-primary checkbox-inline">
            <input name="menus[]" type="checkbox"
                   @if($roleNavMenus && in_array($item['id'], $roleNavMenus )  || !$roleNavMenus) checked="checked" @endif
                   class="checkbox-child" dataid="id-{{$item['id']}}" value="{{$item['id']}}">
            <span>{{$item['title']}}</span>
        </label>
    @endif

    @if( isset($item['nodes']) )
        <div style="background-color: #fffffe;padding: 2px" >
            <div style="margin-left: 20px;">
                @component('StarsPeace::component.role-bind-menu', ['datas'=> $item['nodes'] ,'roleNavMenus'=>$roleNavMenus  ])  @endcomponent
            </div>
        </div>
    @endif
 @endforeach
