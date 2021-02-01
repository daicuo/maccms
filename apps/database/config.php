<?php
//扩展后台菜单
DcConfigMerge('admin_menu.addon',[
    [
        'menu_ico'   => 'fa-database',
        'menu_title' => '数据库',
        'menu_module' => 'database',
        'menu_items' => [
            [
                'ico' => 'fa-paw',
                'title' => '数据库管理',
                'target' => '_self',
                'controll' => 'admin',
                'action' => 'index',
                'url' => DcUrlAddon( ['module'=>'database','controll'=>'admin','action'=>'index'] )
            ],
            [
                'ico' => 'fa-wrench',
                'title' => '数据库还原',
                'target' => '_self',
                'controll' => 'import',
                'action' => 'index',
                'url' => DcUrlAddon( ['module'=>'database','controll'=>'import','action'=>'index'] )
            ],
            [
                'ico' => 'fa-send',
                'title' => '数据库转换',
                'target' => '_self',
                'controll' => 'transform',
                'action' => 'index',
                'url' => DcUrlAddon( ['module'=>'database','controll'=>'transform','action'=>'index'] )
            ],
            [
                'ico' => 'fa-code',
                'title' => '执行SQL',
                'target' => '_self',
                'controll' => 'execute',
                'action' => 'index',
                'url' => DcUrlAddon( ['module'=>'database','controll'=>'execute','action'=>'index'] )
            ],
        ]
    ]
]);
//应用配置
return [
    'database_backup_path'     => './datas/backup/',//备份目录
    'database_backup_size'     => '10485760',//单位字节 B
    'database_backup_compress' => true,//是否压缩
    'database_backup_level'    => 5,//压缩级别
];