@foreach( $datas as $key=>$item)
    <tr>
        <td>
            <div class="btn-group">
                <a class="btn btn-xs btn-default" href="{{ route( 'rotate.menu.bind' , ['navId'=>$item['nav_id'] ,'menuId'=>$item['id'] ]) }}"
                   title="绑定" data-toggle="tooltip">
                    <i class="mdi mdi-earth"></i>
                </a>

                <a class="btn btn-xs btn-default" href="{{ route( 'rotate.menu.edit' , ['navId'=>$item['nav_id'] ,'menuId'=>$item['id'] ]) }}"
                   title="编辑" data-toggle="tooltip">
                    <i class="mdi mdi-pencil-box"></i>
                </a>

                <a class="btn btn-xs btn-default act-stars-remove" href="{{ route( 'rotate.menu.remove' , ['navId'=>$item['nav_id'] ,'menuId'=>$item['id'] ]) }}"
                   title="删除" data-toggle="tooltip">
                    <i class="mdi mdi-window-close"></i>
                </a>

            </div>
        </td>
        <td>  @for($i=0;$i< ($item['level']-1) *3;$i++) &nbsp; @endfor {{ $item['title'] }}</td>
        <td>{{ $item['route_name'] }}</td>
        <td>{{ $item['href'] }}</td>
        <td>{{ $item['icon'] }}</td>
        <td>{{ $item['order'] }}</td>
        <td>{{ $item['status'] }}</td>
    </tr>

    @if( isset($item['nodes']) )
        @component('StarsPeace::component.navmenu', ['datas'=> $item['nodes'] ])  @endcomponent
    @endif
 @endforeach
