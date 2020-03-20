<?php
namespace Stars\Peace\Service;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Stars\Peace\Entity\TemplateCodeEntity;
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
     * @throws FileNotFoundException
     */
    public function templateInfo(Request $request ,int $navId,int $menuId , $fileMd5  )
    {
        //满足条件时，同步磁盘到数据库的模板数据
        if( !$request->only(['nav','template_name']) ){
            $this->refreshTemplateContent2Database() ;
        }

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

            // 如果出现异常，包括数据库、文件写入，系统自动将上次使用中的模板重新覆盖下模板文档
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
        $requestAll=$request->all();
        $backVersion = $request->input("backVersion");
        if(!$backVersion){
            //如果没有提供版本号，则返回改文件的所有版本号
            return $this->templateVersionList( $requestAll );
        }
        try{

           $useIngTemplateInfo = TemplateCodeEntity::where([
               'id'=>$backVersion ,'nav_id'=>$request['validNavId']
           ])->first();
           if($useIngTemplateInfo){
               DB::beginTransaction();

               //等于1的重置为无效
               TemplateCodeEntity::setStatus(['status'=>TemplateCodeEntity::STATUS_USE_ING] , ['status'=>TemplateCodeEntity::STATUS_STOP]);

               //将此改为使用中
               $useIngTemplateInfo->status= TemplateCodeEntity::STATUS_USE_ING;
               $useIngTemplateInfo->save();

               $tempInfo = $useIngTemplateInfo->toArray();

               DB::commit();

               return $this->putFileTemplateContent( $this->viewsPath.'/'.$tempInfo['template_filename'], $tempInfo['template_code'] );
           }

           return false;

       }catch (\Exception $exception){
           DB::rollBack();
           return false;
       }
    }

    /**
     * 返回当前模板所有版本列表
     * @param array $requestAll
     * @return mixed
     */
    private function templateVersionList( array $requestAll ){

        return TemplateCodeEntity::templateAllVersion( $requestAll['templateName'] , $requestAll['validNavId'] );
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

    /**
     * 同步磁盘模板文件内容到数据库
     * @param Request $request
     * @return bool
     * @throws FileNotFoundException
     */
    public function refreshTemplateContent2Database(){

        $disk = new Filesystem();
        $templateFiles = $this->themeTemplates();
        if(!$templateFiles){
            return false;
        }

        $navService = new NavService();
        $navs = $navService->articleNav();
        $navs = $navs ? array_column( $navs->toArray() , 'id'): [];
        $navMenuService= new NavMenuService();

        $template2Navs = [];
        foreach ($navs as $navId){
            $templates = $navMenuService->navMenus(  $navId );
            $tem= array_column( $templates , 'nav_id','template_name' );
            $template2Navs = array_merge( $template2Navs , $tem );
        }

        try{
            DB::beginTransaction();

            //清除
            TemplateCodeEntity::setStatus( [ 'status'=>TemplateCodeEntity::STATUS_USE_ING ] , ['status'=>TemplateCodeEntity::STATUS_STOP] );

            //开始同步
            foreach ($templateFiles as $file){
                if( strstr( strtolower($file) , '.blade.php')){
                    $navId = isset( $template2Navs[$file]) ? $template2Navs[$file] : null;
                    if($navId){
                        $content =( $disk->get( $this->viewsPath .'/'.$file ) );
                        TemplateCodeEntity::storage( $navId , $file , $content , TemplateCodeEntity::STATUS_USE_ING  );
                    }
                }
            }

            DB::commit();
        }catch (\Exception $exception){

            DB::rollBack();

            return false;
        }
        return true;
    }
}
