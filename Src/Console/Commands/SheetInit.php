<?php

namespace Stars\Peace\Console\Commands;


class SheetInit extends PeacePeace
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stars:init_sheet {entityName} {isCore?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化一个 sheet模板配置';

    /**
     * 定义参数名称
     * @var string
     */
    protected $entityName = 'entityName';

    /**
     * 指定类型
     * @var string
     */
    protected $type = 'sheet';

    /**
     * sheet Name
     * @var string
     */
    protected $sheetName = '';

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
        try{
            $sheetClassName = $this->nameSpaceName ."\\{$this->entityValue}" ;
            if(!class_exists($sheetClassName)){
               throw new \Exception($sheetClassName . ' sheet file not exists !');
            }

            if(!(new $sheetClassName)->initialize()){
                throw new \Exception( "{$this->entityValue } Initialize Fail !" );
            }

            $this->info("Initialize {$this->sheetName} Success !");

        }catch (\Exception $exception){

            $this->error( $exception->getMessage() );
        }
    }

    /**
     * @return mixed|void
     */
    public function replaceStubContent()
    {

    }
}
