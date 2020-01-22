
@if( isset($datas) && method_exists($datas , 'links') )
    {{ $datas->links() }}
@endif