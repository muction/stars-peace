<?php

namespace Stars\Peace\Console\Commands;


use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class EntityMake extends PeacePeace
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stars:make_entity {entityName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建一个Stars实体模型文件';

    /**
     * 定义参数名称
     * @var string
     */
    protected $entityName = 'entityName';

    /**
     * 指定类型
     * @var string
     */
    protected $type = 'entity';

    /**
     * @var bool
     */
    protected $isCore = true ;

    /**
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function handleCommand()
    {
        $this->saveFileContent( $this->replaceStubContent() );
        $this->info('Create '.$this->entityName.' Entity Success !');
    }

    /**
     * 替换模板内容
     * @return array|string|string[]
     * @throws Exception
     */
    protected function replaceStubContent()
    {
        return str_replace( 'ENTITYNAME', $this->entityValue ,
            $this->subTemplateContent()
        );
    }
}
