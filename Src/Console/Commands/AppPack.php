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
class AppPack extends AppPatch
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:pack {args*}';

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
    private $gitRepoDir = "/Users/muction/Studio/apps/develop/package/extends/stars-peace/";

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
     * 有效参数，type=file 指文件全路径，多个用空格标记  ,type=git 指git 提交id ,多个用空格标出
     * @var string
     */
    private $args = "";

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

        //输入的文件
        $this->args = $this->argument('args') ;

        //设置补丁保存目录
        $this->packSaveDir = config('stars.common.update.saveDir');

        if(!is_dir($this->packSaveDir)){
            mkdir($this->packSaveDir , 0777 , true );
        }

        //分流处理
        if($this->type == 1){
            $this->packZipFiles( $this->args );

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
    private function packZipFiles( array $files ){

        $files = $this->validFiles( $files  );
        if($files['inValid']){
            $this->error("文件不存在，无法进行打包: ". implode("," , $files['inValid']) );
            return false;
        }

        $patchFileName = $this->makePatchName();

        foreach ( $files['valid'] as $file ){
            exec("zip {$this->packSaveDir}/{$patchFileName}.zip -m ".base_path( $file ) );
        }

        $this->info("打包完成");
    }

    /**
     * 给到git 提交的commid 自动打包所有文件
     */
    private function packByGitCommit(){
        $command = "git -C {$this->gitRepoDir} show a10db0bd55b0fb9cdd26751ad257a61bf9fc4422 --name-only";
        dump($command) ;
        exec($command , $output );
        dd($output);
    }

}
