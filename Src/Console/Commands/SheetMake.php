<?php

namespace Stars\Peace\Console\Commands;


class SheetMake extends PeacePeace
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:sheet {entityName} {isCore?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new stars sheet file';

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
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \Exception
     */
    public function handleCommand()
    {
        $this->saveFileContent( $this->replaceStubContent() );
        $this->info('Create '.$this->entityName.' Sheet Success !');
    }

    /**
     * 替换模板内容
     * @return mixed
     * @throws \Exception
     */
    protected function replaceStubContent()
    {
        return str_replace(
            ['__SHEETNAME__',  '__NAME_SPACE__'],
            [$this->entityValue , $this->nameSpaceName ],
            $this->subTemplateContent()
        );
    }
}
