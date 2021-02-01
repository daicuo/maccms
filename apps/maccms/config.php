<?php
//扩展分类表字段
DcConfigMerge('custom_fields.term_meta', [
    'term_api_url',
    'term_api_params',
    'term_api_tid',
    'term_api_type'
]);
//扩展后台菜单
DcConfigMerge('admin_menu.addon',[
    [
        'menu_ico'    => 'fa-film',
        'menu_title'  => '影视',
        'menu_module' => 'maccms',
        'menu_items'  => [
            [
                'ico' => 'fa-gear',
                'title' => '全局设置',
                'target' => '_self',
                'controll' => 'admin',
                'action' => 'index',
                'url' => DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'index'])
            ],
            [
                'ico' => 'fa-gear',
                'title'=>'资源过滤',
                'target' => '_self',
                'controll'=>'admin',
                'action'=>'filter',
                'url'=>DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'filter'])
            ],
            [
                'ico' => 'fa-gear',
                'title'=>'微信设置',
                'target' => '_self',
                'controll'=>'admin',
                'action'=>'weixin',
                'url'=>DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'weixin'])
            ],
            [
                'ico' => 'fa-gear',
                'title'=>'广告设置',
                'target' => '_self',
                'controll'=>'admin',
                'action'=>'poster',
                'url'=>DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'poster'])
            ],
            [
                'ico' => 'fa-gear',
                'title'=>'首页轮播',
                'target' => '_self',
                'controll'=>'admin',
                'action'=>'slite',
                'url'=>DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'slite'])
            ],
            [
                'ico' => 'fa-gear',
                'title'=>'友情链接',
                'target' => '_self',
                'controll'=>'admin',
                'action'=>'filter',
                'url'=>DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'link'])
            ],
            [
                'ico' => 'fa-gear',
                'title' => '导航管理',
                'target' => '_self',
                'controll' => 'index',
                'action' => 'index',
                'url' => '../nav/index?op_module=maccms'
            ],
            [
                'ico' => 'fa-gear',
                'title' => '分类管理',
                'target' => '_self',
                'controll' => 'index',
                'action' => 'index',
                'url' => '../category/index?op_module=maccms'
            ],
            [
                'ico' => 'fa-home',
                'title' => '前台首页',
                'target' => '_self',
                'controll' => 'admin',
                'action' => 'home',
                'target' => '_blank',
                'url' => DcUrlAdmin('maccms/index/index', '', '')
            ],
        ]
    ]
]);