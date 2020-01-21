<?php
namespace Stars\Peace\Controller;

use Illuminate\Support\Facades\Auth;
use Stars\Peace\Entity\NavMenu;
use Stars\Peace\Service\NavMenuService;
use Stars\Peace\Service\NavService;
use Stars\Rbac\Entity\UserEntity;

class RotateController extends PeaceController
{
   public function index(NavService $navService, NavMenuService $navMenuService){

      // dd( UserEntity::loginUserInfo( ) ->toArray());
       $trees = $navMenuService->tree(1);

       // 树行菜单
       $articleSides= $navMenuService->articleTree( 2 );

       //文章管理导航
       $articleNav =$navService->articleNav();
       return $this->view( 'index' , ['sidebar' => $trees ,'articleNav'=>$articleNav ,'articleSides'=>$articleSides] );
   }
}
