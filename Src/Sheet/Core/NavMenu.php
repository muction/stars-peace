<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class NavMenu extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('导航菜单','nav_menus', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addIntColumn('导航ID', 'nav_id');
        $this->addIntColumn('父菜单ID', 'parent_id' );
        $this->addIntColumn('导航图片ID', 'image_id' );

        $this->addTextWidget('菜单名称', 'title' ,255);
        $this->addTextWidget('路由名称', 'route_name', 255);
        $this->addTextWidget('外链地址','href',255);
        $this->addTextWidget('ICON名称','icon',255);
        $this->addVarCharColumn('模板选择','template_name',512,
            $this->columnOptions(
                $this->optionDefault('')
            ));
        $this->addCharColumn('模板类型','template_type',10,
            $this->columnOptions(
                $this->optionDefault(1)
            ));
        $this->addIntColumn('级别', 'level', 2);
        $this->addNumberWidget('排序', 'order', 11 ,
            $this->columnOptions(
                $this->optionDefault(10)
            )
        );

        $this->addTextWidget('SEO标题', 'seo_title', 255);
        $this->addTextWidget('SEO关键字', 'seo_keywords', 512);
        $this->addTextWidget('SEO介绍', 'seo_description', 512);


        $this->addRadioGroupWidget('状态', 'status', 3,
            $this->columnOptions(
                $this->optionPublicStatic(),
                $this->optionDefault(1)
        ));

    }

}
