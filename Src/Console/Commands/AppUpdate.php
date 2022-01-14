<?php
namespace Stars\Peace\Console\Commands;
use Stars\Tools\Foundation\PatchApply;

/**
 * 客户端更新补丁文件
 *   在服务器端打包好补丁文件后，在终端运行此命令可以进行打补丁
 *  例如： php artisan app:update 补丁版本号（文件名称，不含扩展名）
 * Class AppUpdate
 * @package Stars\Peace\Console\Commands
 */
class AppUpdate extends AbstractAppPatch
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
     * 补丁包文件名
     * @var string
     */
    private $patchFileName ="";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stars:app_update {patchFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '给当前App系统应用补丁包';

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
            $this->patchFileName = $this->argument("patchFile");

            if(!file_exists( $this->pathSaveDir . $this->patchFileName )){
                throw new \Exception("补丁包文件不存在");
            }

            $result= PatchApply::apply(
                $this->workDir,
                $this->pathSaveDir ,
                $this->argument("patchFile")
            );

            $msg = $result == true ? '成功' : '失败' ;
            $this->info("应用结果：{$msg}");

        }catch (\Exception $exception){

            $this->warn( '应用补丁包异常 :'. $exception->getMessage() .', Line'. $exception->getFile() .'-' .$exception->getLine() );
        }
    }
}
