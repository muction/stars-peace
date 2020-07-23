
@php( $__selectActiveValue= old( $column['db_name'] , isset($column['now_value']) ? $column['now_value'] : '' )  )
<select class="form-control" name="{{ $column['db_name'] }}" style="width: 300px">
    <option value="">--请选择--</option>
    @if ( isset( $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE] ) )
        @php( $__sheetValueTableValue= $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['data'])
        @php( $__sheetValueTableField= $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['column'])

        @foreach($__sheetValueTableValue as $_index=>$_item)
            <option value="{{ $_item[$__sheetValueTableField['value']] }}"
                    @if(  $_item[$__sheetValueTableField['value']]  == $__selectActiveValue ) selected="selected" @endif>
                {{ $_item[$__sheetValueTableField['title'] ]}}
            </option>
        @endforeach
    @endif

    @if( isset( $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] ) )
        @php( $__sheetValueSelect=  $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_SELECT] )
        @if(isset($__sheetValueSelect[0]))
            @foreach( $__sheetValueSelect as $_index=>$_value)
                <option value="{{ $_value['value'] }}"
                        @if( $_value['value'] ==$__selectActiveValue ) selected="selected" @endif
                >
                    {{ $_value['title'] }}
                </option>
            @endforeach
        @else
            <option value="{{ $__sheetValueSelect['value'] }}"
                    @if( $__selectActiveValue ==$__sheetValueSelect['value'] ) selected="selected" @endif
            >
                {{ $__sheetValueSelect['title'] }}
            </option>
        @endif
    @endif


    @if ( isset( $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_CALLBACK] ) )
        @php( $__sheetValueCallBackValue= $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_CALLBACK]['data'])
        @php( $__sheetValueCallBackField= $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_CALLBACK]['column'])

        @foreach($__sheetValueCallBackValue as $_index=>$_item)
            @if(isset( $_item[$__sheetValueCallBackField['children']]) )
                <optgroup label="{{$_item[$__sheetValueCallBackField['title']]}}">
                    @foreach ($_item[$__sheetValueCallBackField['children']] as $_nodes_index=>$_nodes_item)
                        <option value="{{ $_nodes_item[$__sheetValueCallBackField['value']] }}"
                                @if(  $_nodes_item[$__sheetValueCallBackField['value']]  == $__selectActiveValue ) selected="selected" @endif>
                            {{ $_nodes_item[$__sheetValueCallBackField['title'] ]}}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $_item[$__sheetValueCallBackField['value']] }}"
                        @if(  $_item[$__sheetValueCallBackField['value']]  == $__selectActiveValue ) selected="selected" @endif>
                    {{ $_item[$__sheetValueCallBackField['title'] ]}}
                </option>
            @endif
        @endforeach
    @endif
</select>
