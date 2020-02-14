<?php
namespace Stars\Peace\Controller;

use Illuminate\Http\Request;
use Stars\Peace\Service\SlideService;
use Stars\Peace\Service\SlideTypeService;

/**
 * 幻灯片
 * Class SlideController
 * @package Stars\Peace\Controller
 */
class SlideController extends PeaceController
{
    //
    /**
     * @param Request $request
     * @param SlideTypeService $slideTypeService
     * @param SlideService $slideService
     * @param int $typeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request , SlideTypeService $slideTypeService, SlideService $slideService , $typeId =0 ){

        $datas = $slideService->allSlide( $typeId );
        $typeInfo = $slideTypeService->info( $typeId );
        return $this->view( 'slide.index' , ['datas'=>$datas, 'typeInfo'=>$typeInfo ] );
    }

    /**
     * @param Request $request
     * @param SlideService $slideService
     * @param SlideTypeService $slideTypeService
     * @param $typeId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addPage(Request $request , SlideService $slideService, SlideTypeService $slideTypeService , $typeId){

        $typeInfo= $slideTypeService->info( $typeId );
        return $this->view( "slide.form"  , ['info'=>[] ,'typeInfo'=>$typeInfo ]);
    }

    /**
     * @param Request $request
     * @param SlideService $slideService
     * @param SlideTypeService $slideTypeService
     * @param $typeId
     * @param $infoId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editPage(Request $request , SlideService $slideService, SlideTypeService $slideTypeService , $typeId , $infoId ){

        $info = $slideService ->info($infoId );
        $typeInfo= $slideTypeService->info( $typeId );
        return $this->view( "slide.form"  , ['info'=>$info  ,'typeInfo'=>$typeInfo ]);

    }

    /**
     * @param SlideService $slideService
     * @param $typeId
     * @param $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove( SlideService $slideService, $typeId, $infoId ){
        $withError=['messageError'=> '保存失败'];
        if( $slideService->remove( $typeId , $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route( 'rotate.slide.index' , ['typeId'=>$typeId ]  ) )->withErrors( $withError) ;

    }

    /**
     * @param Request $request
     * @param SlideService $slideService
     * @param $typeId
     * @param $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addPageStorage( Request $request, SlideService $slideService, $typeId , $infoId=0 ){

        $this->validate( $request , [ 'title'=>'required'] );
        $withError=['messageError'=> '保存失败'];
        if( $slideService->storageSide( $request ,$typeId , $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.slide.index', ['typeId'=> $typeId]  ) )->withErrors( $withError) ;
    }

    /**
     * 分类列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function slideType( SlideTypeService $slideTypeService ){
        $datas = $slideTypeService->all();
        return $this->view( 'slide.type.index' , ['datas'=>$datas ] );

    }

    /**
     * 添加分类页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addTypePage(){

        return $this->view( 'slide.type.form' );
    }

    /**
     * 编辑分类
     * @param SlideTypeService $slideTypeService
     * @param $infoId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editTypePage(SlideTypeService $slideTypeService , $infoId){

        $info = $slideTypeService->info( $infoId );
        return $this->view( 'slide.type.form' , ['info'=>$info ] );
    }

    /**
     * 删除分类
     * @param SlideTypeService $slideTypeService
     * @param $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function typeRemove( SlideTypeService $slideTypeService , $infoId){

        $withError=['messageError'=> '保存失败'];
        if( $slideTypeService->remove( $infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route( 'rotate.slide.index.type'  ) )->withErrors( $withError) ;
    }

    /**
     * 幻灯片分类
     * @param Request $request
     * @param SlideTypeService $slideTypeService
     * @param int $infoId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addTypePageStorage(Request $request , SlideTypeService $slideTypeService, $infoId=0 ){
        $this->validate( $request , ['title'=>'required' ,'order'=>'required'] );
        $withError=['messageError'=> '保存失败'];
        if( $slideTypeService->storageType( $request ,$infoId ) ){
            $withError = ['messageInfo'=> '保存成功'] ;
        }
        return redirect( route('rotate.slide.index.type'  ) )->withErrors( $withError) ;
    }
}

