<?php

namespace Stars\Peace\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;


abstract class PeacePeace extends Command
{

    /**
     * 实体名称，可以是sheet service名称
     * @var string
     */
    protected $entityName =null;

    /**
     * 输入的实体值
     * @var null
     */
    protected $entityValue = null ;

    /**
     * 是否为core操作
     * @var bool
     */
    protected $isCore = null ;

    /**
     * 操作类型
     * @var null
     */
    protected $type = null ;

    /**
     * 文件系统
     * @var null
     */
    protected $files=null;

    /**
     * 文件的namespace
     * @var null
     */
    protected $nameSpaceName = null;

    /**
     * 支持的类型
     * @var array
     */
    protected $allowType =['Sheet'];


    /**
     * 获取模板文件
     * @return string
     * @throws \Exception
     */
    final protected function subTemplateContent()
    {
        return $this->files->get(
            __DIR__ .'/../Stub/'.  strtolower($this->type) .'.stub'
        );
    }

    /**
     * 生成文件目录
     * @return string
     */
    private function savePathName()
    {
        if( $this->isCore ){
            return dirname(dirname(__DIR__)). '/'. ( $this->type) . '/'.( $this->entityValue ) .'.php';
        }

        $entityPath = app_path(( $this->type) );
        if(!is_dir($entityPath)) mkdir( $entityPath );
        return $entityPath .'/'.( $this->entityValue).'.php';
    }

    /**
     * 获取命名空间
     * @return string
     */
    private function getNameSpace()
    {
        if( $this->isCore ){
            return "Stars\Peace\Sheet\Core";
        }
        return "App\\". $this->type;

    }

    /**
     * 初始化一些内容
     */
    final public function handle(Filesystem $filesystem ){

        $this->files = $filesystem ;
        $this->type  = ucfirst( $this->type );
        $this->isCore = $this->isCore==null ? strtolower( $this->argument('isCore' ) ) == 'core' : $this->isCore ;
        $this->nameSpaceName = $this->getNameSpace();
        if($this->entityName){
            $this->entityValue = $this->argument( $this->entityName  );
            $this->entityValue = ucfirst( $this->entityValue ). ucfirst( $this->type );
        }
        $this->handleCommand() ;
    }

    /**
     * 目标文件是否存在
     * @return bool
     */
    final private function hasFile(){

        if($this->files->exists($this->savePathName())){
            $this->error('Create '.$this->type.':'.(  $this->entityName  ).' File Error , File Exists !');
            return false;
        }
    }

    /**
     * 保存这个文件
     * @param $content
     * @return mixed
     */
    final public function saveFileContent( $content ){
        $this->hasFile() ;
        return $this->files->put( $this->savePathName() , $content );
    }

    /**
     * 高级处理
     * @return mixed
     */
    abstract protected function handleCommand();

    /**
     * 替换模板文件
     * @return mixed
     */
    abstract protected function replaceStubContent();

}
