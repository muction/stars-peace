<?php
return [

    //后台百度地图key
    'map'=>[
        'key'=>[
            'baidu'=> '',
        ]
    ],


    /**
     * 配置项已站点支持语言为维度进行切分
     * 例如：网站支持 zh,en两种语言，则使用默认配置即可，支持其他语言则以此类推即可。
     */

    //key 为支持语言简写， value为对应的导航id值
    'nav'=> [
        'en' => 7,
        'zh' => 2
    ],


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

    ]
];