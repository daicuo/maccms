<?php
/*
** 插件基础信息
*/
//\think\Lang::load(APP_PATH.'demo/common/zh-cn.php');
return [
    //插件唯一标识
    'module'   => 'database',
    //插件名称
    'name'     => '数据库管理',
    //插件描述
    'info'     => '数据库管理插件、包含SQL执行，数据库备份、还原、优化等数据维护功能！',
    //插件版本
    'version'  => '1.2.1',
    //依赖数据库
    'datatype' => ['sqlite', 'mysql'],
    //依赖插件版本
    'rely'     => [
        'daicuo'=>['1.4.0-99','1.5.0-99'],
    ],
];