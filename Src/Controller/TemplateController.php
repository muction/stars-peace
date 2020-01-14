<?php
namespace Stars\Peace\Controller;


use Stars\Peace\Service\NavMenuService;
use Stars\Peace\Service\TemplateService;
use Illuminate\Http\Request;

/**
 * 模板操作
 * Class TemplateController
 * @package Stars\Peace\Controller
 */
class TemplateController extends PeaceController
{

    /**
     * 编辑模板：
     *  模板内容全部以数据库存储的为主，私自更改模板文件会造成修改后覆盖的风险
     * @param TemplateService $templateService
     * @param NavMenuService $navMenuService
     * @param $navId
     * @param $menuId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request, TemplateService $templateService, NavMenuService $navMenuService , $navId, $menuId ){
        $menuInfo = $navMenuService->info($menuId);
        if(isset($menuInfo->template_name) && !$menuInfo->template_name || !isset($menuInfo->template_name) ){
            throw new \Exception("菜单没有绑定模板");
        }

        $fileMd5      = $request->input('file_md5');
        $templateInfo = $templateService->templateInfo( $navId, $menuId , $fileMd5,  $menuInfo ? $menuInfo->toArray() : [] );
        return $this->view('template.index' ,[ 'versions'=>$templateInfo['versions'] ,'code'=>$templateInfo['code'] ,'model'=>$templateInfo['model'] ]);
    }
}
