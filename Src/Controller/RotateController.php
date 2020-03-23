<?php
namespace Stars\Peace\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Stars\Peace\Entity\NavMenuEntity;
use Stars\Peace\Service\NavMenuService;
use Stars\Peace\Service\NavService;
use Stars\Peace\Service\RoleService;
use Stars\Rbac\Entity\UserEntity;

/**
 * 后台框架页
 * Class RotateController
 * @package Stars\Peace\Controller
 */
class RotateController extends PeaceController
{
   public function index(NavService $navService, NavMenuService $navMenuService , RoleService $roleService){

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

       //实时获取当前角色所拥有的的文章管理权限
       $roleNavMenus = UserEntity::loginUserInfo('menus');
       $hasSuppersRole =  UserEntity::hasRole( 'root');
       return $this->view( 'index' , ['sidebar' => $trees ,'articleSides'=>$articleSides ,'roleNavMenus'=>$roleNavMenus ,
           'hasSuppersRole'=>$hasSuppersRole ] );
   }
}
