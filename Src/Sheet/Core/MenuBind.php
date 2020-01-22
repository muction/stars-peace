<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class MenuBind extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('菜单绑定','menu_binds', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {

        $this->addIntColumn('菜单ID', 'menu_id', 11);
        $this->addVarCharColumn('绑定名称', 'title', 255);
        $this->addVarCharColumn('模型名', 'sheet_name', 255);
        $this->addVarCharColumn('别名', 'alias_name', 255);
        $this->addVarCharColumn('数据表名称', 'table_name', 255);
        $this->addVarCharColumn('绑定设置', 'options', 512);
        $this->addIntColumn('排序', 'order', 11);
        $this->addCharColumn('状态', 'status', 3);

    }

}