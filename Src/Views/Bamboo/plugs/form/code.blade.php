<textarea name="{{ $column['db_name'] }}" id="code_{{ $column['db_name'] }}" >
    {{ old($column['db_name'] , isset($column['now_value']) ? $column['now_value'] :  $_columnDefaultValue ) }}
</textarea>


<script type="text/javascript">
    window.onload=function(){

        let editor = CodeMirror.fromTextArea(document.getElementById("code_{{ $column['db_name'] }}"), {
            lineNumbers: true,
            styleActiveLine: true,
            matchBrackets: true,
            lineWrapping: true,
            theme: "mdn-like"
        });
        editor.setSize('auto', 600)
    }
</script>
