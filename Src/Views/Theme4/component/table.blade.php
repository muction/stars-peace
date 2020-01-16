<div class="table-responsive">
    <table class="table table-bordered">
        @if(isset($tableThead ))
        <thead>
            <tr>
                @foreach( $tableThead as $thead)
                    <th>
                        {{ $thead['title'] }}
                    </th>
                @endforeach
                <th>
                    操作
                </th>
            </tr>
        </thead>
        @endif
        <tbody>

        @if( isset($tableData) && $tableData )
            @foreach( $tableData as $item )
            <tr>
                @foreach( $tableThead as $thead)
                    @if(isset( $item[$thead['key']]))
                    <td>
                        {{ $item[$thead['key']] }}
                    </td>
                    @endif
                @endforeach

                @if( isset($slot) && $slot)
                   <td>{{ $slot }}</td>
                @endif
            </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

@if( isset( $tableData ) && method_exists($tableData  , 'links') )
    {{ $tableData->links() }}
@endif

