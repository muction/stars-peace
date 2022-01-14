<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class Log extends SheetSheet
{


    /**
     * 定义模型信息
     * @return void
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('系统日志表','logs', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return void
     */
    protected function sheetColumn()
    {
        $this->addTinyintColumn( '动作类型' ,'type');
        $this->addVarCharColumn('日志概述','title', 255);
        $this->addVarCharColumn('日志详情','content',512);
    }

}
