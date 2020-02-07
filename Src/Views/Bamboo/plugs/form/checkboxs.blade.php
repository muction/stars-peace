
@php( $__checkboxActiveValue= old( $column['db_name'] , isset($column['now_value']) ? explode(',', $column['now_value']) : [] )  )

@if ( isset( $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE] ) )
    @php( $__sheetValueTableValue= $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['data'])
    @php( $__sheetValueTableField= $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['column'])

    @foreach($__sheetValueTableValue as $_index=>$_item)
        <label class="lyear-checkbox checkbox-inline checkbox-primary">
            <input type="checkbox" name="{{ $column['db_name'] }}[]" value="{{ $_item[$__sheetValueTableField['value']] }}"
                @if( in_array($_item[$__sheetValueTableField['value']] , $__checkboxActiveValue) ) checked="checked" @endif
            >
            <span> {{ $_item[$__sheetValueTableField['title'] ]}}</span>
        </label>
    @endforeach
@endif

@if( isset( $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] ) )
    @php( $__sheetValueSelect=  $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] )
    @if(isset($__sheetValueSelect[0]))
        @foreach( $__sheetValueSelect as $_index=>$_value)
            <label class="lyear-checkbox checkbox-inline checkbox-primary">
                <input type="checkbox" name="{{ $column['db_name'] }}[]" value="{{ $_value['value'] }}"
                       @if( in_array( $_value['value'] , $__checkboxActiveValue) ) checked="checked" @endif

                >
                <span> {{ $_value['title'] }}</span>
            </label>
        @endforeach
    @else
        <label class="lyear-checkbox checkbox-inline checkbox-primary">
            <input type="checkbox" name="{{ $column['db_name'] }}[]" value="{{ $__sheetValueSelect['value'] }}"
                   @if( in_array( $__sheetValueSelect['value'] , $__checkboxActiveValue) ) checked="checked" @endif

            >
            <span>  {{ $__sheetValueSelect['title'] }}</span>
        </label>
    @endif
@endif

