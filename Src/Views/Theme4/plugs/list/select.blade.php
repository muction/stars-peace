@php( $__selectActiveValue= $valueItem[$_listColumnName]  )


@if ( isset( $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE] ) )
    @php( $__sheetValueTableValue= $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['data'])
    @php( $__sheetValueTableField= $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['column'])

    @foreach($__sheetValueTableValue as $_index=>$_item)
        @if(  $_item[$__sheetValueTableField['value']]  == $__selectActiveValue )
            {{ $_item[$__sheetValueTableField['title'] ]}}
            @break
        @endif
    @endforeach
@endif

@if( isset( $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] ) )
    @php( $__sheetValueSelect=  $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] )
    @if(isset($__sheetValueSelect[0]))
        @foreach( $__sheetValueSelect as $_index=>$_value)
            @if( $_value['value'] ==$__selectActiveValue )
                {{ $_value['title'] }}
                @break
            @endif
        @endforeach
    @else
        @if( $__selectActiveValue ==$__sheetValueSelect['value'] )
            {{ $__sheetValueSelect['title'] }}
        @endif
    @endif
@endif