@php( $mapKey=config('stars.map.key.baidu')  )

<div class="card">
    <div class="card-header">
        <div class="form-group"  style="width: 200px; margin-right: 5px">
            <label class="sr-only" for="example-if-email">地点</label>
            <input class="form-control" type="email" id="example-if-email" name="example-if-email" placeholder="请输入邮箱..">
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="button">查询</button>
        </div>
    </div>
    <div class="card-body">
        <div id="map_{{$mapKey}}" style="height: 300px"></div>
    </div>
</div>

@component( "StarsPeace::component.map.baidu" , ['mapKey'=> $mapKey ,'container'=>'map_'.$mapKey ] )
    <script type="text/javascript">


        console.log( map ) ;
    </script>
@endcomponent
