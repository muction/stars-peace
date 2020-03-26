
@php( $__date= isset($column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DATE_FORMAT]['date']) ? $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DATE_FORMAT]['date'] : "")
@php( $__plug= isset($column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DATE_FORMAT]['page']) ? $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DATE_FORMAT]['page'] : "" )
@php( $__type= isset($column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DATE_FORMAT]['type']) ? $column['options'][\Stars\Peace\Foundation\SheetSheet::OPTION_KEY_DATE_FORMAT]['type'] : "" )
<input type="text"
       id="timer_{{ $column['db_name'] }}"
       class="form-control input-sm timerSelect"
       readonly="readonly"
       name="{{ $column['db_name'] }}"
       value="{{ old($column['db_name'] , isset($column['now_value']) ? date( $__date , strtotime( $column['now_value'] )) : date( $__date )) }}"
>
<script type="text/javascript">

    $(function(){
        laydate.render({
            elem: "#timer_{{ $column['db_name'] }}" ,
            format : "{{ $__plug }}"
            ,type: "{{ $__type }}"
        });
    });
</script>

