<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class Attachment extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('系统附件','attachments', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {
        $this->addIntColumn('绑定ID', 'bind_id', 11);
        $this->addVarCharColumn('原始文件名','original_name',255);
        $this->addIntColumn('文件大小', 'size', 11);
        $this->addCharColumn('md5','md5',32);
        $this->addCharColumn('hash','hash',64);
        $this->addVarCharColumn('保存路径','save_file_path',255);
        $this->addVarCharColumn('保存文件名' ,'save_file_name', 255);
        $this->addVarCharColumn('文件扩展名', 'type',255);
        $this->addVarCharColumn('文件描述', 'summary',255);
        $this->addVarCharColumn('MIME', 'mime',255);
        $this->addCharColumn('可用状态', 'status',3);
        $this->addCharColumn('来源', 'source',50);


    }

}