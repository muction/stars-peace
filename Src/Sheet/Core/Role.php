<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class Role extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('系统角色','roles', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addVarCharColumn('角色名称','title', 255);
        $this->addVarCharColumn('显示名称','display_name', 255);
        $this->addVarCharColumn('描述','description', 255);
        $this->addCharColumn('状态','status', 3);
    }

}