<?php

namespace Stars\Peace\Console\Commands;

use Illuminate\Console\Command;
use Stars\Peace\Foundation\SheetSheet;
use Stars\Peace\Entity\StarsInit as StarInitData;

/**
 *
 *
 * 检查星际CMS系统版本
 *  1、创建所有Core 里的sheet模型
 *  2、写入系统初始化数据
 * Class Stars
 * @package Stars\Peace\Console\Commands
 */
class StarsVersion extends PeacePeace
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Stars:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get system version';

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
     * 系统版本
     */
    const VERSION = "4.0";

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
        $this->info( "当期版本: ".self::VERSION );
    }


    public function replaceStubContent()
    {
        // TODO: Implement replaceStubContent() method.
    }
}
