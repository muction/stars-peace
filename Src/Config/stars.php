<?php
return [

    //后台百度地图key
    'map'=>[
        'key'=>[
            'baidu'=> '',
        ]
    ],


    //以下是每个nav对应的配置

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