<a class="btn btn-xs btn-default"

   href="{{ route( 'rotate.article.articles' , ['navId'=>$navId ,'menuId'=>$menuId , 'bindId'=>$bindId, 'action'=>'edit' ,'infoId'=>$valueItem['id']]) }}"
   title="编辑" data-toggle="tooltip">
    <i class="mdi mdi-pencil-box"></i>
</a>

<a class="btn btn-xs btn-default article-stars-remove"

   href="{{ route(  'rotate.article.articles' , ['navId'=>$navId ,'menuId'=>$menuId , 'bindId'=>$bindId, 'action'=>'remove' ,'infoId'=>$valueItem['id']]) }}"
   title="删除" data-toggle="tooltip">
    <i class="mdi mdi-window-close"></i>
</a>