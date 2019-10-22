<?php
namespace Stars\Peace\Controller;

use Stars\Peace\Entity\NavMenu;
use Stars\Peace\Service\NavService;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class NavController extends PeaceController
{
    /**
     * nav list
     * @param NavService $navService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
   public function index(NavService $navService){

       $navs = $navService->pagination();
       return $this->view( 'nav.index' , ['datas' => $navs ] );
   }

    /**
     *create a new nav
     */
   public function add(){

       return $this->view('nav.form');
   }

    /**
     * storage request
     * @param Request $request
     * @param NavService $navService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
   public function storage(Request $request , NavService $navService){

       $this->validate($request , [
           'title'=>'required' ,
           'remark'=>'required' ,
           'article'=>'required'
       ]);
        $withError=['messageError'=> '保存失败'];
        if( $navService->storage( $request )){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
       return redirect( route('rotate.nav.add') )->withErrors(
           $withError
       ) ;
   }

    /**
     * remove a nav
     * @param NavService $navService
     * @param $navId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
   public function remove(NavService $navService , $navId){

       $result =$navService->remove( $navId );
       if(!$result){
           return redirect(route('rotate.nav.index'))
               ->withErrors(['messageError'=> '包含子菜单禁止删除！']);
       }
       return redirect( route('rotate.nav.index') ) ;
   }

    /**
     * @param NavService $navService
     * @param $navId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(NavService $navService , $navId ){

        $info = $navService->info( $navId ) ;
        return $this->view( 'nav.form' ,['info'=>$info ] );
   }
}
