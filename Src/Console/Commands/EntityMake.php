<?php

namespace Stars\Peace\Console\Commands;


class EntityMake extends PeacePeace
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:entity {entityName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new stars core entity file for private';

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
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    public function handleCommand()
    {
        $this->saveFileContent( $this->replaceStubContent() );
        $this->info('Create '.$this->entityName.' Entity Success !');
    }

    /**
     * 替换模板内容
     * @return mixed
     * @throws \Exception
     */
    protected function replaceStubContent()
    {
        return str_replace( 'ENTITYNAME', $this->entityValue ,
            $this->subTemplateContent()
        );
    }
}
