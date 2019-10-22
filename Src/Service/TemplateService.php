<?php
namespace Stars\Peace\Service;

use Stars\Peace\Foundation\ServiceService;
use Illuminate\Filesystem\Filesystem;

class TemplateService extends ServiceService
{
    /**
     * 自动返回 resources/views/ 下所有模板文件
     * @return array
     */
    public function themeTemplates( ){

        $path = resource_path('views');

        $themeFiles = [];
        $file =new Filesystem();
        $files= $file->allFiles( $path  ,true );
        foreach ($files as $index=>$file) {
            $themeFiles[] = $file->getRelativePathname();
        }

        return $themeFiles;
    }
}