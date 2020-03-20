<?php
namespace Stars\Peace\Lib;

/**
 *
 * Class Option
 * @package Stars\Peace\Lib
 */
class Option
{
    /**
     * 上传来源
     */

    //常规上传
    const ATTACHMENT_UPLOAD_CLIENT =  'normal';

    //裁剪上传
    const ATTACHMENT_UPLOAD_CROPPER = 'cropper';

    /**
     * 分页
     */

    //文章分页
    const ARTICLE_PAGE_SIZE =14 ;

    //管理员分页
    const USER_PAGE_SIZE = 15;

    /**
     * 系统类别设置
     */
    const OPTION_TYPE_SYS = 1;

    /**
     * 系统初始化时创建
     * 系统目录配置 相对于 base_path 而言
     */
    const OPTION_SYSTEM_CORE_DIR = [
        'fixs',
        'public/fixs'
    ];
}
