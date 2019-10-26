<input type="text" class="form-control input-sm datetimepicker-input" readonly="readonly" name="{{ $column['db_name'] }}" value="{{ old($column['db_name'] , isset($column['now_value']) ? $column['now_value'] : date('Y-h-d H:i:s')) }}">

