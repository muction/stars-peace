<ul class="nav nav-tabs">

    <li>
        <div style="margin-top: 5px;margin-right:20px ;">
            <form class="form-inline"
                  action="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindId ]) }}"
                  method="get" >
                <div class="form-group">
                    <label class="sr-only" for="example-if-keyword">关键字</label>
                    <input class="form-control input-sm" type="text" id="example-if-keyword" name="keyword" value="{{$keyword}}" placeholder="关键字..">
                </div>
                <div class="form-group">
                    <button class="btn btn-default btn-sm" type="submit">搜索</button>
                </div>
            </form>
        </div>
    </li>

    @foreach( $menuBindInfo as $bindInfo )

        <li @if( $bindId == $bindInfo['id'] && !$action ) class="active" @endif >
            <a href="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindInfo['id']]) }}">
                {{ $bindInfo['title'] }}
            </a>
        </li>

        @if( $bindId == $bindInfo['id'] )
            <li @if( $action == 'add' ) class="active" @endif >
                <a href="{{ route('rotate.article.articles', ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindInfo['id'] ,'action'=>'add' ]) }}">
                    <i class="mdi mdi-pen"></i>新增
                </a>
            </li>
        @endif
    @endforeach


</ul>

