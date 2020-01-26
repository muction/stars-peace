
<input type="text" class="form-control input-sm"
       id="{{ $column['db_name'] }}"
       name="{{ $column['db_name'] }}"
       value="{{ old( $column['db_name'] , isset($column['now_value']) ? $column['now_value'] : $_columnDefaultValue ) }}" />
