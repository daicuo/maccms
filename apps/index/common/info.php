<?php
/*
** 插件基础信息
*/
return [
    'module'=>'index',//插件唯一标识
    'name'=>'首页',
    'info'=>'首页插件是默认自带的应用，主要的是提供插件开发、安装、卸载的演示！',
    'version'=>'1.0.0',
    //后台菜单
    'ico'=>'fa-home',
    'subico'=>'fa-gear',
    'nav'=>'首页',
    'subnav'=>[
        [
            'title'=>'基本设置',
            'controll'=>'index',
            'action'=>'index',
            'link'=>DcUrlAddon( ['module'=>'index','controll'=>'admin','action'=>'index'] )
        ],
    ],
    //依赖数据库
    'datatype'=>['sqlite', 'mysql'],
    //依赖插件版本
    'rely'=>[
        'daicuo'=>['1.2.0-19'],
        //'category'=>'1.0.1',
    ]
];