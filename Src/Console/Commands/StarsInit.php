<?php
namespace Stars\Peace\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Stars\Peace\Foundation\SheetSheet;
use Stars\Peace\Entity\StarsInit as StarInitData;
use Stars\Peace\Lib\Option;

/**
 * 初始化一个干净的Stars系统
 *  1、创建所有Core 里的sheet模型
 *  2、写入系统初始化数据
 * Class Stars
 * @package Stars\Peace\Console\Commands
 */
class StarsInit extends PeacePeace
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Stars:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Stars System';

    /**
     * 超级管理员登录名
     * @var string
     */
    protected $initRootName = '';

    /**
     * 超级管理员密码
     * @var string
     */
    protected $initRootPassword = '';

    /**
     * 核心文件
     */
    protected $isCore = true;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handleCommand()
    {
        while ( !($this->initRootPassword && $this->initRootName) )
        {
            if(!$this->initRootName){
                $this->initRootName = $this->ask("Please input Root User Name ");
            }

            if(!$this->initRootPassword){
                $this->initRootPassword = $this->ask("Please input Root User Password");
            }
        }

        //创建必须目录
        $this->createSystemDir();

        //创建核心文件
        $this->createCoreSheet() ;

        //初始化数据
        $this->createInitData() ;

    }

    /**
     * 创建系统基础模型
     */
    protected function createCoreSheet()
    {
        $this->info("start scan all core sheets ...");
        $allCoreSheets= SheetSheet::sheets(true);
        foreach ($allCoreSheets as $sheet){
            $this->initSheet( $sheet );
        }
        $this->info('Create Core Sheet Success ...');
    }

    /**
     * 初始化
     * @param $sheet
     * @return bool
     */
    protected function initSheet( $sheet )
    {
        try {
            $this->info('init core sheet : '.$sheet );
            $sheet = ($this->nameSpaceName).'\\'.$sheet;
            $td = new $sheet();
            $td->initialize();

            return true;
        } catch (\Exception $exception) {
            $this->warn("init core sheet {$sheet} exception : {$exception->getMessage()}");
            return false;
        }
    }
    /**
     * 写入系统必备初始化数据
     */
    protected function createInitData(){

       try{
           $this->info("start create init data ...");
           $stars = new StarInitData();
           $stars->start( $this->initRootName, $this->initRootPassword );
           $this->info('create init data success !');
       }catch (\Exception $exception){
           $this->error('CreateInitData Error : '.$exception->getMessage() );
           return false;
       }
    }

    public function replaceStubContent()
    {
        return "";
    }


    /**
     * 创建核心目录
     */
    private function createSystemDir(){

        $this->info("初始化核心目录");
        $system = new Filesystem();
        foreach (Option::OPTION_SYSTEM_CORE_DIR as $dir){
            $path =base_path($dir);
            if(!$system->isDirectory( $path )){
                $system->makeDirectory( $path);
                $this->info("创建目录: {$path}");
            }
        }
        $this->info("初始化目录完成");
    }
}
