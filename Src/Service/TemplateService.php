<?php
namespace Stars\Peace\Service;

use App\Entity\TemplateCodeEntity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Filesystem\Filesystem;

class TemplateService extends ServiceService
{
    private $viewsPath = "";

    public function __construct()
    {
        $path = config('view.paths');
        $this->viewsPath = isset($path[0]) ? $path[0] : '';
    }

    /**
     * 自动返回 resources/views/ 下所有模板文件
     * @return array
     */
    public function themeTemplates( ){



        if(!$this->viewsPath ){
            return  [];
        }
        $themeFiles = [];
        $file =new Filesystem();
        $files= $file->allFiles( $this->viewsPath  ,true );
        foreach ($files as $index=>$file) {
            $themeFiles[] = $file->getRelativePathname();
        }

        return $themeFiles;
    }

    /**
     * 获取模板代码
     * @param int $navId
     * @param int $menuId
     * @param string $fileMd5
     * @param array $menuInfo
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function templateInfo(Request $request ,int $navId,int $menuId , $fileMd5  )
    {
        $templateContent = '';
        $templateDataSource= '';

        //文章导航
        $navs = new NavService();
        $articleNavs = $navs->articleNav();
        $firstArticleNavId=  $articleNavs ? $articleNavs->toArray() : [];
        $validNavId = $request->input('nav', isset($firstArticleNavId[0]) ? $firstArticleNavId[0]['id'] : 0 );
        $validNavInfo = $navs->info($validNavId);

            //有效菜单
        $navMenus = new NavMenuService();
        $navMenus = $navMenus->tree( $validNavId );


        //所有模板文件
        $templateFiles = $this->themeTemplates();

        //当前编辑的模板
        $templatePathName = $request->input('template_name');

        if($templatePathName){

            //取得数据库模板数据
            $templateContent = TemplateCodeEntity::where([
                'status'=> TemplateCodeEntity::STATUS_USE_ING,
                'nav_id'=> $validNavId ,
                'template_filename' => $templatePathName
            ])->value('template_code');
            if($templateContent){
                $templateDataSource= '数据库';
            }else if(!$templateContent){

                //再加载文件
                $fileSystem = new  Filesystem();
                if($fileSystem->exists( $this->viewsPath.'/'.$templatePathName )){
                    $templateContent = $fileSystem->get( $this->viewsPath.'/'.$templatePathName );
                    $templateDataSource= '模板文件';
                }
            }
        }

        return [ 'articleNavs'=>$articleNavs ,
            'navMenus'=>$navMenus ,
            'validNavId'=>$validNavId ,
            'validNavInfo'=>$validNavInfo,
            'templateName'=>$templatePathName ,
            'templateContent'=>$templateContent ,
            'templateDataSource'=>$templateDataSource ,
            'templateFiles'=>$templateFiles
        ] ;
    }

    /**
     * 应用更改
     * @param Request $request
     * @return mixed
     */
    public function apply(Request $request){
        $requestAll = $request->all();
        $update =null;
        $create= null;
        $writeFileResult = null;

        //先取得当前正在使用的模板数据
        $useIngTemplateInfo = TemplateCodeEntity::where(
                [
                    'status'=> TemplateCodeEntity::STATUS_USE_ING,
                    'nav_id'=>$requestAll['validNavId']  ,
                    'template_filename'=>$requestAll['templateName']
                ]
            )->first();

        try{
            DB::beginTransaction();

            //将当前 status=1 使用中的置位 0
            $update= TemplateCodeEntity::setStatus(
                [
                    'status'=> TemplateCodeEntity::STATUS_USE_ING,
                    'nav_id'=>$requestAll['validNavId']  ,
                    'template_filename'=>$requestAll['templateName']
                ] ,
                ['status'=>TemplateCodeEntity::STATUS_STOP ]);

            //写入新的模板文件
            $create= TemplateCodeEntity::storage( $requestAll['validNavId'] , $requestAll['templateName'] , $requestAll['templateContent'] , TemplateCodeEntity::STATUS_USE_ING) ;
            DB::commit();

        }catch (\Exception $exception){
            DB::rollBack();

            //取得最后一条信息
            if($useIngTemplateInfo){
                $useIngTemplateInfo = $useIngTemplateInfo->toArray();
                $this->putFileTemplateContent( $this->viewsPath.'/'.$useIngTemplateInfo['template_filename'], $useIngTemplateInfo['template_code'] );
            }

            return false;
        }

        //文件操作，写入新的文件内容
        $this->putFileTemplateContent( $this->viewsPath.'/'.$requestAll['templateName'] ,  $requestAll['templateContent']);
        return ['update'=>$update ,'create'=>$create ,'writeFile'=>$writeFileResult ];
    }

    /**
     * 回滚到上次版本
     * @param Request $request
     * @return bool|int
     */
    public function rollBack(Request $request ){
       try{
           $requestAll=$request->all();
           $useIngTemplateInfo = TemplateCodeEntity::where(
                   [
                       'status'=> TemplateCodeEntity::STATUS_STOP ,
                       'nav_id'=>$requestAll['validNavId']  ,
                       'template_filename'=>$requestAll['templateName']
                   ]
               )->orderBy('updated_at','DESC')->first();
           if($useIngTemplateInfo){
               $useIngTemplateInfo = $useIngTemplateInfo->toArray();
               return $this->putFileTemplateContent( $this->viewsPath.'/'.$useIngTemplateInfo['template_filename'], $useIngTemplateInfo['template_code'] );
           }
           return false;
       }catch (\Exception $exception){
           return false;
       }
    }

    /**
     * 具体写入文件
     * @param $templateName
     * @param $templateContent
     * @return bool|int
     */
    public function putFileTemplateContent( $templateName, $templateContent ){
        $file = new Filesystem();
        if(!$file->exists($templateName)){
            return false;
        }
        return $file->put( $templateName  , $templateContent);
    }

}
