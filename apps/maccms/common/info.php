<?php
/*
** 插件基础信息
*/
return [
    'module'=>'maccms',//插件唯一标识
    'name'=>'青苹果API影视系统',
    'info'=>'青苹果API影视系统于20200903开始开发，主要是用于测试DaiCuo1.2.6的BUG与功能。',
    'version'=>'1.0.0',
    //后台菜单
    'ico'=>'fa-home',
    'subico'=>'fa-gear',
    'nav'=>'影视',
    'subnav'=>[
        [
            'title'=>'基本设置',
            'controll'=>'index',
            'action'=>'index',
            'link'=>DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'index'],'')
        ],
        [
            'title'=>'资源添加',
            'controll'=>'index',
            'action'=>'site',
            'link'=>DcUrlAddon(['module'=>'maccms','controll'=>'admin','action'=>'site'],'')
        ],
    ],
    //依赖插件版本
    'rely'=>[
        'daicuo'=>['1.2.0-19'],
        //'category'=>'1.0.1',
    ]
];