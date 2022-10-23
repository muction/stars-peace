@php( $__selectActiveValue= $valueItem[$_listColumnName]  )


@if ( isset( $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE] ) )
    @php( $__sheetValueTableValue= $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['data'])
    @php( $__sheetValueTableField= $sheetColumns[$_listColumnName]['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_VALUE_TABLE]['column'])
    @php( $__selectValueTableDbName = $sheetColumns[$_listColumnName]['db_name'] ?? '')

    @foreach($__sheetValueTableValue as $_index=>$_item)
        @if(  $_item[$__sheetValueTableField['value']]  == $__selectActiveValue )
            <a
                href="{{ route(  'rotate.article.articles' , ['navId'=>$navId ,'menuId'=>$menuId , 'bindId'=>$bindId ,'infoId'=>$valueItem['id'] ,'_select_table_column'=>$__selectValueTableDbName,'_select_table_value_'=> $_item[$__sheetValueTableField['value']]  ] ) }}"
            >
                {{ $_item[$__sheetValueTableField['title'] ]}}</a>
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
