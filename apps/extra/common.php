<?php
return [
    'site_name'=>'DaiCuo',
    'site_status'=>'off',
    'site_close'=>'Site closed',
    'site_theme'=>'default_pc',
    'wap_theme'=>'default_wap',
    'site_secret'=>'abcdefghijklmnopqrst',
    'custom_fields'=>[
        'term_much'=>['term_much_type','term_much_info','term_much_parent','term_much_count'],
        'term_meta'=>['term_tpl'],
        'user_meta'=>['user_capabilities'],
    ],
    'user_roles'=>[
        'guest' => [
            'admin/index/login',
            'admin/index/logout',
            //'index/login/?url=test',
        ],
        'administrator' => [
            '*',
        ]
    ],
];