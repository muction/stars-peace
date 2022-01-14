<?php
namespace Stars\Peace\Console\Commands;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Stars\Tools\Foundation\PatchMake;
use Stars\Tools\Lib\Patch\AppMakePatchOption;
use Stars\Tools\Lib\Patch\MakePatch;

/**
 * 为了上线后部署及更新方便，开发了更新打包命令
 *
 *  1、php artisan stars:app_pack [file1,file2,file3....] | [git:commitId]
 *  2、php artisan stars:app_pack
 *
 *  所有打包操作都是以 appPath 为根基地址操作的
 *
 * Class AppPack
 * @package App\Console\Commands
 */
class AppPack extends AbstractAppPatch
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stars:app_pack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '为 StarsPeace 系统制作补丁文件';

    /**
     * 打包保存的地址
     * @var Repository|mixed|string
     */
    private $packSaveDir = "";

    /**
     * git 仓库目录地址
     * @var string
     */
    private $gitRepoDir = "";

    /**
     * 工作目录
     * @var string
     */
    private $workDir ="";

    /**
     * 打包类型
     * @var string
     */
    private $type = "";

    /**
     * 允许的操作类型
     * @var array
     */
    private $allowType = [1,2];

    /**
     * @var string
     */
    private $readMeFile = "";

    /**
     * 核心处理
     * @return mixed|void
     * @throws \Exception
     */
    public function working()
    {

        //获取运行模式
        while (!$this->type){
            $input = $this->ask("请选择运行模式：1以指定文件打包，2 以git提交记录打包");
            if( in_array($input , $this->allowType) ){
                $this->type = $input;
            }
        }
        //工作目录
        $this->workDir = base_path(). DIRECTORY_SEPARATOR ;

        //GIT仓库目录
        $this->gitRepoDir = $this->workDir;

        //设置补丁保存目录
        $this->packSaveDir = rtrim( config('stars.common.update.saveDir') ,'/').DIRECTORY_SEPARATOR ;

        if(!is_dir($this->packSaveDir)){
            mkdir($this->packSaveDir , 0777 , true );
        }

        if( $this->type == 1 ){
            return $this->packZipFiles() ;
        }elseif( $this->type == 2){
            return $this->packByGitCommit() ;
        }else{
            return null;
        }
    }

    /**
     * 给到一些文件进行zip打包
     * @return bool
     */
    private function packZipFiles( ): bool
    {

        try{
            $files = "";
            while ( !$files ){
                $input = $this->ask("请输入要加入压缩包的相对文件，多个用空格：");
                if($input){
                    $files = $input;
                }
            }
            $files = explode(" ", $files) ;
            $result = PatchMake::makeFilePatch( $this->workDir , $this->packSaveDir ,$files );
            $this->info("制作完成: {$result}");

        }catch (\Exception $exception){

            $this->error("打包出现异常：{$exception->getMessage()}");
        }
    }

    /**
     * 给到git 提交的commid 自动打包所有文件
     */
    private function packByGitCommit(){
        try{
            $commitIds = null;
            while ( !$commitIds ){
                $input = $this->ask("请输入git提交ID，多个用空格：");
                if($input){
                    $commitIds = $input;
                }
            }
            $commitIds = explode(" ", $commitIds) ;
            $result = PatchMake::makeGitPatch(
                $this->workDir ,
                $this->packSaveDir  ,
                $this->gitRepoDir ,
                $commitIds
            );

            $this->info("制作完成: {$result}");

        }catch (\Exception $exception){

            $this->error("获取GIT提交时异常：".$exception->getMessage() );
        }
    }
}
