
@php( $__selectActiveValue= explode(',', $valueItem[$_listColumnName])   )


@if ( isset( $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE] ) )
    @php( $__sheetValueTableValue= $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['data'])
    @php( $__sheetValueTableField= $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['column'])

    @foreach($__sheetValueTableValue as $_index=>$_item)
        @if( in_array($_item[$__sheetValueTableField['value']] , $__selectActiveValue ) )
            <span class="label label-purple">{{ $_item[$__sheetValueTableField['title'] ]}}</span>
        @endif
    @endforeach
@endif

@if( isset( $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] ) )
    @php( $__sheetValueSelect=  $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] )
    @if(isset($__sheetValueSelect[0]))
        @foreach( $__sheetValueSelect as $_index=>$_value)
            @if( in_array(  $_value['value'] , $__selectActiveValue ) )
                <span class="label label-success">{{ $_value['title'] }}</span>
            @endif
        @endforeach
    @else
        @if( in_array( $__sheetValueSelect['value']  , $__selectActiveValue ) )
            <span class="label label-purple">{{ $__sheetValueSelect['title'] }}</span>
        @endif
    @endif
@endif
