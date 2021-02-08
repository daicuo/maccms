<?php
/*
** 插件基础信息
*/
return [
    //插件唯一标识
    'module'=>'maccms',
    //插件名称
    'name'=>'青苹果影视系统',
    //插件描述
    'info'=>'MacCms是基于DaiCuo开发框架研发的一款WEB应用，使用MacCms可以快速搭建一个免更新维护的影视聚合、影视导航、影视点播网站。',
    //插件版本
    'version'=>'1.2.5',
    //依赖数据库
    'datatype' => ['sqlite', 'mysql'],
    //依赖插件版本
    'rely'     => [
        'daicuo' => ['1.4.0-99'],
    ],
];