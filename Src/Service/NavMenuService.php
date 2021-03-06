<?php
namespace Stars\Peace\Service;

use Stars\Peace\Entity\MenuBindEntity;
use Stars\Peace\Entity\NavMenuEntity;
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
        $only = $request->only(['parent_id' ,'title' ,'route_name' ,'image_id' ,'href' ,'icon' ,
            'order' ,'template_name','template_type' ,'seo_title' ,'seo_keywords', 'seo_description']) ;
        if($only['parent_id']){
            $detail = NavMenuEntity::detail($navId, $only['parent_id'] );
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
            return NavMenuEntity::edit( $navId, $request->input('id') , $only  );
        }

        return NavMenuEntity::storage( array_merge( ['nav_id'=>$navId ,'level'=>$level] , $only ) );
    }

    /**
     * data list
     * @return mixed
     */
    public function pagination(){
        return NavMenuEntity::paginatePage( 15 );
    }

    /**
     * @param $navId
     * @param bool $withBinds
     * @return array
     */
    public function tree($navId ,$withBinds=false ){
        $navMenu = new NavMenuEntity();
        return $navMenu->tree( $navId,$withBinds );
    }

    /**
     * @param $navId
     * @param $status
     * @return array
     */
    public function articleTree( $navId , $status = null ){

        $navMenu = new NavMenuEntity();
        $nodes= $navMenu->getNodes( $navId , false  ,$status );
        if($nodes)
            foreach ($nodes as $index=>$item){
                $menuBindInfos = $item->binds->toArray();
                if(isset($menuBindInfos[0])){
                    $nodes[$index]->target = "articleContent";
                    $nodes[$index]->url = makeArticleUrl( $navId, $item['id'] , $menuBindInfos[0]['id']  );
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

        return NavMenuEntity::remove( $navId , $menuId );
    }

    /**
     * info
     * @param $menuId
     * @return mixed
     */
    public function info( $menuId ){

        return NavMenuEntity::info( $menuId ) ;
    }

    /**
     * detail
     * @param $navId
     * @param $menuId
     * @return mixed
     */
    public function detail($navId, $menuId ){

        return NavMenuEntity::detail($navId,  $menuId ) ;
    }

    /**
     * @param $navId
     * @param bool $withMenuBind
     * @return mixed
     */
    public function navMenus( $navId , $withMenuBind=false ){
        $navMenu = new NavMenuEntity();
        return $navMenu->getNodes( $navId ,$withMenuBind)->toArray();
    }

    /**
     * 路径导航
     * @param $menuId
     * @return array
     */
    public function crumbs( $menuId ){

        return NavMenuEntity::pathMenus( $menuId );
    }

    /**
     * @param $routerName
     * @return mixed
     */
    public function findByRouterName( $routerName ,$activeNavId ){
        $info= NavMenuEntity::where('nav_id', $activeNavId )
            ->where( 'route_name' ,'=' ,$routerName )->first();
        return $info ? $info->toArray() : [];
    }

}
