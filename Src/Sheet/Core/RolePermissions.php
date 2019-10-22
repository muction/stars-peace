<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class RolePermissions extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('角色与权限关系','role_permissions', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addIntColumn('角色ID', 'role_id', 11);
        $this->addIntColumn('权限ID', 'permission_id', 11);
    }

}