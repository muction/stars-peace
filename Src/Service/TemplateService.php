<?php
namespace Stars\Peace\Service;

use App\Entity\TemplateCodeEntity;
use Illuminate\Http\Request;
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
        //dd($templateFiles);
        if($templatePathName){
            $fileSystem = new  Filesystem();
            $templateContent = $fileSystem->get( $this->viewsPath.'/'.$templatePathName );
        }

        return [ 'articleNavs'=>$articleNavs ,
            'navMenus'=>$navMenus ,
            'validNavId'=>$validNavId ,
            'validNavInfo'=>$validNavInfo,
            'templateName'=>$templatePathName ,
            'templateContent'=>$templateContent ,
            'templateFiles'=>$templateFiles
        ] ;
    }

}
