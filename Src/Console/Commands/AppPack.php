<?php

namespace Stars\Peace\Console\Commands;

use Illuminate\Console\Command;

/**
 * 为了上线后部署及更新方便，开发了更新打包命令
 *
 *  1、php artisan app:pack [file1,file2,file3....] | [git:commitId]
 *  2、php artisan app:pack
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
    protected $signature = 'app:pack';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stars system for App Pack Update file';

    /**
     * 打包保存的地址
     * @var \Illuminate\Config\Repository|mixed|string
     */
    private $packSaveDir = "";

    /**
     * git 仓库目录地址
     * @var string
     */
    private $gitRepoDir = "";

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

        //设置补丁保存目录
        $this->packSaveDir = config('stars.common.update.saveDir');

        if(!is_dir($this->packSaveDir)){
            mkdir($this->packSaveDir , 0777 , true );
        }

        //分流处理
        if($this->type == 1){
            $this->packZipFiles();

        }elseif ($this->type ==2){
            $this->packByGitCommit();

        }else{

        }
    }

    /**
     * 给到一些文件进行zip打包
     * @param array $files
     * @return bool
     */
    private function packZipFiles( ){

        try{
            $files = "";
            while ( !$files ){
                $input = $this->ask("请输入要加入压缩包的相对文件，多个用空格：");
                if($input){
                    $files = $input;
                }
            }

            $files = explode(" ", $files ) ;
            $this->zipFiles( $files );

        }catch (\Exception $exception){

            $this->error("打包出现异常：{$exception->getMessage()}");
        }
    }

    /**
     * 压缩文件
     * @param array $files
     * @return bool
     * @throws \Exception
     */
    private function zipFiles(array $files ){
        try{
            $files = $this->validFiles(  $files );
            if($files['inValid']){
                $this->error("文件不存在，无法进行打包: ". implode("," , $files['inValid']) );
                return false;
            }

            //生成readme 文件
            $patchPathFileName = $this->makePatchPathName( $this->packSaveDir );
            $files['valid'][]  = $this->makeReadMeFile( $files['valid'] );

            //拼接命令
            $zipFiles = implode(" ", $files['valid']);
            exec("zip {$patchPathFileName} {$zipFiles}");

            //计算补丁md5
            $fileMd5= md5_file( $patchPathFileName );

            //删除readme文件
            unlink($this->readMeFile);

            //重新命名文件
            $newPatchFileName= $patchPathFileName.'-'.$fileMd5.'.zip';
            if( rename(  $patchPathFileName , $newPatchFileName ) ){
                $this->info("打包完成: {$newPatchFileName}");
            }else{
                $this->error("打包失败");
            }
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    /**
     * 生成readme文件
     * @param array $files
     * @return bool
     */
    private function makeReadMeFile(array $files ){

        $fileName = 'readme.txt' ;
        $this->readMeFile = base_path($fileName);
        if(!file_exists(   $this->readMeFile )){
            touch($this->readMeFile );
        }
        file_put_contents( $this->readMeFile , "本次更新文件如下： \r\n");
        foreach ($files as $file){
            file_put_contents(   $this->readMeFile , $file."\r\n" , FILE_APPEND);
        }
        return $fileName;
    }

    /**
     * 给到git 提交的commid 自动打包所有文件
     */
    private function packByGitCommit(){
       // $command = "git -C {$this->gitRepoDir} show a10db0bd55b0fb9cdd26751ad257a61bf9fc4422 --name-only";

        try{
            $this->gitRepoDir = base_path();

            $commitIds = "";
            while ( !$commitIds ){
                $input = $this->ask("请输入git提交ID，多个用空格：");
                if($input){
                    $commitIds = $input;
                }
            }

            $files = [];
            $commitIds = explode(" ", $commitIds);
            foreach ($commitIds as $commitId ){
                exec("git -C ".$this->gitRepoDir ." show {$commitId} --name-only", $outPut );
                if( $outPut ){
                    $outPut = array_reverse($outPut );
                    foreach ($outPut as $line){
                        if( file_exists( base_path($line) ) ){
                            $files[] = $line;
                        }
                    }
                }
            }

            $this->zipFiles( $files );

        }catch (\Exception $exception){

            $this->error("获取GIT提交时异常：".$exception->getMessage() );
        }
    }
}
