<?php

namespace Stars\Peace\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem ;
use Stars\Tools\Foundation\PatchApply;

/**
 * 客户端更新补丁文件
 *   在服务器端打包好补丁文件后，在终端运行此命令可以进行打补丁
 *  例如： php artisan app:update 补丁版本号（文件名称，不含扩展名）
 * Class AppUpdate
 * @package Stars\Peace\Console\Commands
 */
class AppUpdate extends AppPack
{
    /**
     * 工作目录
     * @var string
     */
    private $workDir = "";

    /**
     * 补丁下载目录
     * @var string
     */
    private $pathSaveDir = "";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update {patchFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'App Client Update';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function working()
    {
        try{
            $this->workDir = base_path().'/' ;
            $this->pathSaveDir = base_path('fixs/');

            $result= PatchApply::apply(
                $this->workDir,
                $this->pathSaveDir ,
                $this->argument("patchFile")
            );

            $msg =$result == true ? '成功' : '失败' ;
            $this->info("应用结果：{$msg}");

        }catch (\Exception $exception){

            $this->warn( 'Apply Patch Exception :'. $exception->getMessage() .', Line'. $exception->getFile() .'-' .$exception->getLine() );
        }
    }
}
