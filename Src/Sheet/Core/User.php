<?php
namespace Stars\Peace\Sheet\Core;
use Stars\Peace\Foundation\SheetSheet;

/**
 * 系统模型文件
 * @package Stars\Peace\Sheet
 */
class User extends SheetSheet
{


    /**
     * 定义模型信息
     * @return mixed
     */
    protected function sheetInfo()
    {
        $this->makeSheetInfo('用户信息','users', '1.1.0');
    }

    /**
     * 定义字段集，支持方法请参考SheetField方法
     * @return mixed
     */
    protected function sheetColumn()
    {

        $this->addTextWidget('登录名', 'username', 255);
        $this->addPasswordWidget('密码', 'password', 64,
            $this->columnOptions(
                $this->optionEncryptionHash(),
                $this->optionValidator('confirmed|required')
            ))
        ;
        $this->addTextWidget('邮箱','email', 255);
        $this->addTextWidget('手机号', 'phone', 11);
        $this->addIntColumn( '头像', 'portrait' );
        $this->addTextWidget('部门', 'branch', 50,
            $this->columnOptions(
                $this->optionValueTable('categories', 'title', 'id', 'id=1','id ASC')
            ));
        $this->addRadioGroupWidget('状态','status', 3 ,
            $this->columnOptions(
                $this->optionPublicStatic()
            ));

        $this->addDatetimeColumn('最后登录时间', 'last_login_time' );

    }

}