<script type="text/javascript" src="http://api.map.baidu.com/api?v=3.0&ak={{$mapKey}}"></script>
<script type="text/javascript">
    var map = new BMap.Map("{{$container}}");
    var point = new BMap.Point(116.404, 39.915);
    map.centerAndZoom(point, 15);
</script>

{{-- slot javacript 自定义 --}}
{{ $slot }}