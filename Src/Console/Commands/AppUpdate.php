<?php

namespace Stars\Peace\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem ;

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
     * 补丁下载目录
     * @var string
     */
    private $pathSaveDir = "";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update {version}';

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

            $system = new Filesystem();

            //补丁下载目录
            $this->pathSaveDir = base_path("fixs");

            if( !$system->isDirectory( $this->pathSaveDir) ) {
                $system->makeDirectory($this->pathSaveDir , 0777 ,true ) ;
            }

            //update version
            $updateVersion = $this->argument("version")  ;
            $patchPath = $this->getPatchPathName( $updateVersion );

            if( !file_exists( $patchPath ) ){
                throw new \Exception("Patch File Not Found: {$patchPath}");
            }

            //tmp 目录 fixs/20200119/PATCH.20200119.1320
            $prefixPatch = date('Ymd').'/'. substr( $updateVersion , 0 , strpos($updateVersion , '-'));
            $tmpBaseDir = $this->pathSaveDir .'/' . $prefixPatch ;
            $tmpUpdateDir = $tmpBaseDir .'/patch/';
            $tmpBackDir = $tmpBaseDir .'/back/';

            if(!$system->isDirectory( $tmpUpdateDir)){
                $system->makeDirectory( $tmpUpdateDir, 0755 , true ) ;
            }

            if(!$system->isDirectory($tmpBackDir)){
                $system->makeDirectory( $tmpBackDir , 0755 , true ) ;
            }

            if( !$system->isFile($tmpBaseDir.'/update.txt') ){
                $system->put($tmpBaseDir.'/update.txt' ,'') ;
            }

            //解压缩
            $command = "unzip -o -d {$tmpUpdateDir} {$patchPath}";
            exec($command , $outPut);

            //根据readme文件进行操作
            $readMeFile = $tmpUpdateDir.'/readme.txt';
            if( !file_exists( $readMeFile )){
                throw new \Exception("补丁包文件已损坏，请重新下载.");
            }


            $content = $system->get( $readMeFile ) ;
            $updateFiles = explode("\r\n" , $content);
            unset($updateFiles[0]) ;
            $updateFiles = array_filter($updateFiles);
            if(!$updateFiles){
                throw new \Exception("补丁文件损坏了") ;
            }

            //开始更新
            for($i=0 ; $i<3; $i++){
                $system->append( $tmpBaseDir.'/update.txt' , "------ {date('Y-m-d H:i:s')} ------" );
            }

            foreach ($updateFiles as $file){

                $fileDir = $system->dirname( $file);
                if($fileDir == '.'){
                    $fileDir = "";
                }

                $targetOrigin = $tmpBackDir . ( $fileDir ) ;

                //创建对应备份目录
                if( !$system->isDirectory($targetOrigin) ){
                    $system->makeDirectory( $targetOrigin , 0755 , true );
                }

                //备份原文件到目录
                if(! $system->copy( $file , $targetOrigin  ) ){
                    throw new \Exception("备份时失败了");
                }

                //应用当前补丁包文件
                /*if(!$system->copy( $tmpUpdateDir .'/' .$file , $file )){
                    throw new \Exception("应用补丁失败 {$file}");
                }*/

                $system->append( $tmpBaseDir.'/update.txt' , "Apply:". $file );
            }

        }catch (\Exception $exception){

            $this->error( 'Apply Patch Exception :'. $exception->getMessage() .', Line'. $exception->getFile() .'-' .$exception->getLine() );
        }
    }
}
