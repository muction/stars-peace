<?php

namespace Stars\Peace\Controller;

use Stars\Peace\Service\ArticleService;
use Stars\Peace\Service\MenuBindService;
use Stars\Peace\Service\NavMenuService;
use Illuminate\Http\Request;
use Validator;

class ArticleController extends PeaceController
{
    /**
     * 此方法融合了多个方法
     * 文章列表
     * 添加文章
     * 修改文章
     * @param Request $request
     * @param NavMenuService $navMenuService
     * @param MenuBindService $menuBindService
     * @param ArticleService $articleService
     * @param $navId
     * @param int $menuId
     * @param int $bindId
     * @param string $action
     * @return mixed
     * @throws \Exception
     */
    public function menus(Request $request, NavMenuService $navMenuService, MenuBindService $menuBindService, ArticleService $articleService, $navId , $menuId=0, $bindId=0 , $action='' ){

        $assign = [
            'menuBindInfo'=>[] ,
            'sides'=>[] ,
            'bindSheetInfo' => [] ,
            'navId'=>$navId ,
            'menuId'=>$menuId ,
            'bindId'=>$bindId ,
            'action'=>$action ,
            'infoId'=> $request->input('infoId') ,
            'keyword' => $request->input('keyword')
        ];

        if($menuId){
            $assign['menuBindInfo'] = $menuBindService->bindAllInfo( $menuId );
            $assign['menuInfo'] = $navMenuService->info($menuId );
            if ($assign['menuBindInfo'])
                $assign['bindId'] = $bindId ? $bindId : $assign['menuBindInfo'][0]['id'];
                $assign['bindSheetInfo'] = $menuBindService->bindSheetInfo( $menuId, $assign['bindId'] );
                if(!$assign['bindSheetInfo'])
                    throw new \Exception("没有找到 {$assign['menuInfo']['title']} 菜单绑定数据~");
                $articleService= $articleService->init( $assign['bindSheetInfo'] ) ;
         }

        //ACTION 执行删除操作
        if(in_array( $action, ['remove'] ) && $assign['infoId']){
            $removeResult=$articleService->remove($bindId, $assign['infoId'] );
            return redirect( route('rotate.article.articles',
                ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindId ]) );
        }

        //ACTION 执行保存操作，新增或修改
        if( $request->isMethod('POST')){
            $validateRules = [];
            $validateMessages = [];
            $bindRequiredColumns = isset( $assign['bindSheetInfo']['options']['column_required']) ?
                $assign['bindSheetInfo']['options']['column_required'] : [] ;
            foreach ( $bindRequiredColumns as $_index=> $_item ){
                $validateRules[ $_item ] = 'required';
                $validateMessages[ $_item.'.required' ] = '不能为空' ;
            }
            $validateRules ? $this->validate( $request , $validateRules, $validateMessages ) : '' ;
            $result= $articleService->storage(  $request, $assign , $assign['infoId'] );
            return redirect( route('rotate.article.articles',
                ['navId'=>$navId ,'menuId'=>$menuId ,'bindId'=>$bindId ]) );
        }

        // 树行菜单
        $assign['sides'] = $navMenuService->articleTree( $navId );

        //定义模板
        if( !$menuId  ){

            $templateName = "article.index";

        }else if( !$action )
        {
            // 分页获取数据
            $assign['datas'] = $articleService->pagation( $bindId , $assign['keyword'], $articleService->bindListSearchColumns );
            $assign['bindListColumns'] = $articleService->bindListColumns ;
            $assign['sheetColumns'] = $articleService->sheetColumns ;
            $templateName = "article.index.articles";

        }else if( in_array($action, ['add']) )
        {
            $templateName = "article.index.form";

        }else if( in_array($action, [ 'edit']) )
        {

            $assign['bindSheetInfo'] = $articleService->mergeColumnNowValue(  $bindId , $assign['infoId'] );
            $templateName = "article.index.form";

        }else{
            $templateName = "";
        }
        return $this->view( $templateName , $assign );

    }
}
