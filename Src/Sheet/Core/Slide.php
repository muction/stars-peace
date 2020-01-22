<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class Slide extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('幻灯片表','slides', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addIntColumn( "分类ID" , 'slide_type_id' );
        $this->addTextWidget('名称','title', 255);
        $this->addTextWidget('简介', 'summary', 512);
        $this->addTextWidget('URL', 'url', 512) ;
        $this->addIntColumn( "attachment_id" , 'attachment_id' );
        $this->addIntColumn( "排序" , 'order' );
        $this->addRadioGroupWidget('状态','status', 3 ,
            $this->columnOptions(
                $this->optionPublicStatic()
            ));
    }

}
