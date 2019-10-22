<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\MenuBind;
use Stars\Peace\Entity\NavMenu;
use Stars\Peace\Foundation\ServiceService;
use Stars\Peace\Lib\Helper;
use Illuminate\Http\Request;

class NavMenuService extends ServiceService
{
    /**
     * save a nav
     * @param Request $request
     * @param $navId
     * @return mixed
     * @throws \Exception
     */
    public function storage( Request $request ,$navId ){

        $level=1;
        $only = $request->only(['parent_id' ,'title' ,'route_name' ,'image_id' ,'href' ,'icon' ,'order' ,'template_name','template_type']) ;
        if($only['parent_id']){
            $detail = NavMenu::detail($navId, $only['parent_id'] );
            $level = $detail['level'] +1;
        }

        //检测是否有图片
        if( $request->hasFile('image') ){
            $attachmentService = new AttachmentService();
            $fileUploadInfo = $attachmentService->upload( $request ,'user', 'image' );
            if( isset($fileUploadInfo->id) ){
                $only['image_id'] = $fileUploadInfo->id;
            }
        }

        if($request->input('id')){
            $only['level']= $level;
            if( !isset($only['image_id']) || !$only['image_id']){
                $only['image_id'] = 0;
            }
            return NavMenu::edit( $navId, $request->input('id') , $only  );
        }

        return NavMenu::storage( array_merge( ['nav_id'=>$navId ,'level'=>$level] , $only ) );
    }

    /**
     * data list
     * @return mixed
     */
    public function pagination(){
        return NavMenu::paginatePage( 15 );
    }

    /**
     * @param $navId
     * @return array
     */
    public function tree($navId  ){
        $navMenu = new NavMenu();
        return $navMenu->tree( $navId );
    }

    /**
     * @param $navId
     * @return array
     */
    public function articleTree( $navId ){
        $navMenu = new NavMenu();
        $nodes= $navMenu->getNodes( $navId );

        if($nodes)
            foreach ($nodes as $index=>$item){
                $params= [ 'navId'=> $navId , 'menuId'=>$item['id']];
                $menuBindInfos = $item->binds->toArray();
                if(isset($menuBindInfos[0])){
                    $params['bindId'] = $menuBindInfos[0]['id'];
                    $nodes[$index]->target = "articleContent";
                    $nodes[$index]->url = route( 'rotate.article.articles',  $params );
                }

            }
        return $nodes ? Helper::list2Tree( $nodes->toArray() , 'id', 'parent_id' , 'nodes' , 0 ) : [];
    }

    /**
     * remove a nav data
     * @param $navId
     * @param $menuId
     * @return bool
     */
    public function remove( $navId ,$menuId ){

        return NavMenu::remove( $navId , $menuId );
    }

    /**
     * info
     * @param $menuId
     * @return mixed
     */
    public function info( $menuId ){

        return NavMenu::info( $menuId ) ;
    }

    /**
     * detail
     * @param $navId
     * @param $menuId
     * @return mixed
     */
    public function detail($navId, $menuId ){

        return NavMenu::detail($navId,  $menuId ) ;
    }

    /**
     * @param $navId
     * @param bool $withMenuBind
     * @return
     */
    public function navMenus( $navId , $withMenuBind=false ){
        $navMenu = new NavMenu();
        return $navMenu->getNodes( $navId ,$withMenuBind)->toArray();
    }



}
