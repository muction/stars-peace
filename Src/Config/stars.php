<?php
return [

    //后台百度地图key
    'map'=>[
        'key'=>[
            'baidu'=> '',
        ]
    ],

    /**
     * 共有配置
     */
    'common'=>[
        //更新
        'update'=>[
            //打包保存的目录
            'saveDir' => public_path('fixs')
        ],

        //允许访问IP
        'allowIp'=> [] ,

        //拒绝IP
        'denyIp' => [],
    ],

    /**
     * 后台发布文章钩子
     *  钩子需实现 Stars\Peace\Contracts\ArticleHook; 接口
     */
    'hook'=>[
        'articleHook'=> App\Hook\Article::class
    ],

    /**
     * app seo
     * 需继承 Stars\Peace\Foundation\PageSeoFoundation
     */
    'page'=>[
        'seo'=>""
    ],

    /**
     * 配置项已站点支持语言为维度进行切分
     * 例如：网站支持 zh,en两种语言，则使用默认配置即可，支持其他语言则以此类推即可。
     */

    /**
     * 导航简称及导航ID映射配置
     */
    'nav'=> [
        'en' => 7,
        'zh' => 2
    ],


    /**
     * ********************************** 以下是样例配置 ***********************************
     */
    'zh'=>[
        'inner'=> [
            'templateName' => 'detail' ,
            'delimiter'=> '.',
            'count' => 1
        ],
        'cache'=> [
            'navMenu' => 300
        ],

        'paginate'=>[
            'default'=>20 ,
            'single.about.speech'=>15 ,
            'paginate.join.society'=>1,
            'paginate.news.paginate'=>3,
        ] ,

        'options'=>[
            'default'=>[
                ''
            ]
        ] ,

    ],

    // 所有配置项与 zh 一样...
    'en'=>[

    ],

    /**
     * ******************************** 样例配置结束 *************************************
     */

];
