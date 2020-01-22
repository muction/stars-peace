<?php
namespace Stars\Peace\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Stars\Peace\Entity\NavMenu;
use Stars\Peace\Service\NavMenuService;
use Stars\Peace\Service\NavService;
use Stars\Rbac\Entity\UserEntity;

class RotateController extends PeaceController
{
   public function index(NavService $navService, NavMenuService $navMenuService){

       $trees = $navMenuService->tree(1);

       //文章管理导航
       $articleNav =$navService->articleNav();

       // 树行菜单
       $cacheSlideCacheKey = '_article_slides_cache' ;
       $articleSides= Cache::get( $cacheSlideCacheKey , [] );

       if( !$articleSides ){
           foreach ($articleNav as $nav){
               $articleSides[] = [
                   'title'=> $nav['title'] ,
                   'menus'=> $navMenuService->articleTree( $nav->id )
               ];
           }

           Cache::store('file' )->set($cacheSlideCacheKey ,$articleSides , 3600 );

       }


       return $this->view( 'index' , ['sidebar' => $trees ,'articleSides'=>$articleSides] );
   }
}
