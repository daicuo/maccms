<?php
/*
** 插件初始配置
** 调用方法:config('变量名');
*/
//扩展自定义字段
config('custom_fields.term_meta', array_merge(config('custom_fields.term_meta'), ['term_hook'] ));
//扩展后台菜单
config('admin_menu.addon', array_merge(config('admin_menu.addon'),[
    [
        'menu_ico'    => 'fa-home',
        'menu_title'  => '首页',
        'menu_module' => 'index',
        'menu_items'  => [
            [
                'ico' => 'fa-gear',
                'title' => '基本设置',
                'target' => '_self',
                'controll' => 'admin',
                'action' => 'index',
                'url' => DcUrlAddon( ['module'=>'index','controll'=>'admin','action'=>'index'] )
            ],
        ]
    ]
]));
//个性配置或者覆盖
return [
    //config('index_test')
    'index_test' => '我是配置演示',
];