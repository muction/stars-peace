<?php


namespace Stars\Peace\Lib;


class Option
{

    /** 状态码 **/
    const APP_CODE_ERROR = 500;

    const APP_MSG_ERROR = '系统错误';


    /** 上传 **/
    //附件上传
    const ATTACHMENT_UPLOAD_CLIENT =  'normal';

    //裁剪
    const ATTACHMENT_UPLOAD_CROPPER = 'cropper';


    /** 分页 **/
    const ARTICLE_PAGE_SIZE =8 ;
    const USER_PAGE_SIZE = 15;

    /** 系统类别设置 **/
    const OPTION_TYPE_SYS = 1;
}