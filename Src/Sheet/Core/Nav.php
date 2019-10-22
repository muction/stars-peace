<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class Nav extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('导航','navs', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addTextWidget( '名称', 'title', 255 );
        $this->addTextWidget( '描述', 'remark', 255);
        $this->addCharColumn('文章管理', 'article' , 3);
    }

}