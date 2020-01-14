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
     * @param Request $request
     * @param TemplateService $templateService
     * @param int $navId
     * @param int $menuId
     * @param string $fileMd5
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, TemplateService $templateService , $navId=0, $menuId=0 ,$fileMd5='' ){

        $templateInfo = $templateService->templateInfo($request,  $navId, $menuId , $fileMd5  );
        return $this->view('template.index' , $templateInfo );
    }
}
