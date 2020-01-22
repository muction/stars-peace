<?php
namespace Stars\Peace\Controller;

use Stars\Peace\Service\MenuBindService;
use Stars\Peace\Service\NavMenuService;
use Stars\Peace\Service\NavService;
use Stars\Peace\Service\SheetService;
use Stars\Peace\Service\TemplateService;
use Illuminate\Http\Request;

class NavMenuController extends PeaceController
{
    /**
     * menu list
     * @param NavMenuService $navMenuService
     * @param NavService $navService
     * @param $navId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   public function index(NavMenuService $navMenuService, NavService $navService,  $navId ){

       $tree = $navMenuService->tree( $navId );
       $navInfo = $navService->info( $navId );
       return $this->view( 'nav.menu.index' , ['datas' => $tree ,'nav'=>$navInfo ] );
   }

    /**
     *create a new nav menu
     * @param NavService $navService
     * @param TemplateService $templateService
     * @param NavMenuService $navMenuService
     * @param $navId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   public function add( NavService $navService, TemplateService $templateService, NavMenuService $navMenuService, $navId ){
       $themeFiles =  $templateService->themeTemplates();
       $navInfo = $navService->info( $navId );
       $tree = $navMenuService->tree( $navId );
       return $this->view('nav.menu.form', ['nav'=>$navInfo ,'themeFiles'=>$themeFiles ,'tree'=>$tree ] );
   }

    /**
     * edit a nav menu
     * @param NavService $navService
     * @param TemplateService $templateService
     * @param NavMenuService $navMenuService
     * @param $navId
     * @param $menuId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit( NavService $navService, TemplateService $templateService ,NavMenuService $navMenuService ,  $navId, $menuId ){

        $themeFiles =  $templateService->themeTemplates();
        $navInfo = $navService->info( $navId );
        $tree = $navMenuService->tree( $navId );
        $menuInfo = $navMenuService->detail( $navId, $menuId);
        return $this->view('nav.menu.form', ['nav'=>$navInfo ,'themeFiles'=>$themeFiles ,'tree'=>$tree ,'info'=>$menuInfo ] );
    }

    /**
     * storage request
     * @param Request $request
     * @param NavMenuService $navMenuService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
   public function storage(Request $request , NavMenuService $navMenuService, $navId){

       $this->validate($request , [
           'title'=>'required'
       ]);
        $withError=['messageError'=> '保存失败'];
        if( $navMenuService->storage($request ,$navId)){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
       return redirect( route('rotate.menu.add' ,['navId'=>$navId ]) )->withErrors(
           $withError
       ) ;
   }

    /**
     * remove a nav menu
     * @param NavMenuService $navMenuService
     * @param $navId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
   public function remove(NavMenuService $navMenuService , $navId, $menuId ){

       $result = $navMenuService->remove( $navId, $menuId );
       if(!$result){
           return redirect(route('rotate.menu.index' , ['navId'=>$navId ]))
               ->withErrors(['messageError'=> '包含子菜单禁止删除！']);
       }
       return redirect( route('rotate.menu.index'  , ['navId'=>$navId ]) ) ;
   }

    /**
     * bind
     * @param NavMenuService $navMenuService
     * @param NavService $navService
     * @param MenuBindService $menuBindService
     * @param SheetService $sheetService
     * @param $navId
     * @param $menuId
     * @param int $bindId
     * @return void
     */
   public function bind(NavMenuService $navMenuService ,NavService $navService, MenuBindService $menuBindService , SheetService $sheetService, $navId, $menuId, $bindId=0){

       $navInfo = $navService->info( $navId );
       $menuInfo = $navMenuService->detail( $navId, $menuId);
       $allSheets = $sheetService->sheets();
       $assign = ['nav'=>$navInfo ,'info'=>$menuInfo , 'sheets'=>$allSheets ,'bind'=>isset($bind) ? $bind :[] ] ;
       if($bindId){
           $assign['bind'] = $menuBindService->bindInfo( $menuId, $bindId );
            return $this->view('nav.menu.bind_edit', $assign ) ;
       }
       return $this->view('nav.menu.bind' , $assign);
   }

    /**
     * @param Request $request
     * @param MenuBindService $menuBindService
     * @param NavService $navService
     * @param SheetService $sheetService
     * @param $navId
     * @param $menuId
     * @param int $bindId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
   public function bindStorage(Request $request, MenuBindService $menuBindService ,NavService $navService,SheetService $sheetService, $navId, $menuId, $bindId=0){

       $withErrors=['messageError'=> "操作失败了"];
       $result= $menuBindService->navMenuBindSheet($navId, $menuId ,$request ,$bindId );
       if($result)
           $withErrors =['messageInfo'=> "操作成功"];
       return redirect( route('rotate.menu.bind' , ['navId'=>$navId ,'menuId'=>$menuId]) )
           ->withErrors($withErrors);
   }

    /**
     * @param MenuBindService $menuBindService
     * @param $navId
     * @param $menuId
     * @param $bindId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
   public function bindRemove( MenuBindService $menuBindService , $navId, $menuId, $bindId ){

       $withErrors=['messageError'=> "删除绑定失败了"];
       $result= $menuBindService->navMenuBindRemove( $menuId ,$bindId );
       if($result)
           $withErrors =['messageInfo'=> "删除绑定成功"];
       return redirect( route('rotate.menu.bind' , ['navId'=>$navId ,'menuId'=>$menuId]) )
           ->withErrors($withErrors);
   }

    /**
     * @param MenuBindService $menuBindService
     * @param $navId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   public function bindCopy(Request $request, NavService $navService,  MenuBindService $menuBindService , $navId, $targetNavId =0){

       $setUp =$request->get('setUp', 0) ;

       if($setUp == 2){
           $result= $menuBindService->bindCopy( $navId, $targetNavId );
           if($result){
               return redirect( route('rotate.nav.menu.copy' , ['navId'=>$navId ,'targetNavId'=> $targetNavId ,'setUp'=>3 ]) );
           }
       }

       $navs = $navService->pagination();
       $nav = $navService->info( $navId ) ;
       return $this->view('nav.menu.bind_copy' ,['navs'=>$navs , 'nav'=>$nav , 'setUp'=>$setUp ,'targetNavId'=>$targetNavId]);
   }
}
