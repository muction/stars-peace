<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class Options extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('全局配置','options', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addVarCharColumn( "类别" ,'type' , 20 );
        $this->addVarCharColumn( "键名" ,'key' , 255 );
        $this->addVarCharColumn( "值" , 'value' , 255 );
    }

}