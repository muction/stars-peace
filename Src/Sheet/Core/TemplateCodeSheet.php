<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class TemplateCodeSheet extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('模板文件模型','template_codes', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addIntColumn('导航ID', 'nav_id');
        $this->addTextWidget('模板名称', 'template_filename' ,512);
        $this->addEditorWidget("代码" ,'template_code');
        $this->addVarCharColumn("操作者名" ,'author' , 100);
        $this->addRadioGroupWidget('状态', 'status', 3,
            $this->columnOptions(
                $this->optionPublicStatic(),
                $this->optionDefault(1)
            ));

    }

}
