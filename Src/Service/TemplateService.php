<?php
namespace Stars\Peace\Service;

use App\Entity\TemplateCodeEntity;
use Stars\Peace\Foundation\ServiceService;
use Illuminate\Filesystem\Filesystem;

class TemplateService extends ServiceService
{
    /**
     * 自动返回 resources/views/ 下所有模板文件
     * @return array
     */
    public function themeTemplates( ){

        $path = config('view.paths');
        $path = isset($path[0]) ? $path[0] : '';

        if(!$path){
            return  [];
        }
        $themeFiles = [];
        $file =new Filesystem();
        $files= $file->allFiles( $path  ,true );
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
     */
    public function templateInfo(int $navId,int $menuId , $fileMd5,array  $menuInfo )
    {
        $code = null;
        $return = ['versions'=>[] , 'code'=>[] ,'model'=>'未知' ];
        if( $fileMd5 ){
            $codeInfo = TemplateCodeEntity::where('file_md5' , $fileMd5)->first();
            if( $codeInfo ){
                $return['model'] = "数据库";
                $code = $codeInfo->toArray();
                $code = $code['template_code'];
            }
        }

        if(!$code ){
            //从模板文件里加载
            $path = config('view.paths');
            if( isset($path[0]) && $path[0]){
                $templateFilePath = $path[0].'/'.$menuInfo['template_name'] ;
                if( file_exists( $templateFilePath ) ){
                    $return['model'] = "文件";
                    $code = file_get_contents( $templateFilePath );
                }
            }
        }

        $versions = TemplateCodeEntity::where('nav_id' , $navId)->where('menu_id', $menuId)
            ->orderBy('updated_at' ,'DESC')
            ->get();

        $return ['versions'] = $versions;
        $return ['code'] = $code;
        return $return ;
    }

}
