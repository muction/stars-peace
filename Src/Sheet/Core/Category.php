<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class Category extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('系统分类表','categories', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addVarCharColumn('分类名称','title', 255);
        $this->addVarCharColumn('介绍', 'summary', 255);
        $this->addVarCharColumn('ICON', 'icon', 255);
        $this->addIntColumn('父ID' ,'parent_id', 11);
        $this->addVarCharColumn('路径', 'path', 255);
        $this->addVarCharColumn('类型', 'type', 255);
        $this->addIntColumn('排序', 'order', 11);
        $this->addCharColumn('状态', 'status', 3);
        $this->addIntColumn('级别', 'level', 2);
    }

}