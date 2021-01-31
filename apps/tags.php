<?php
// 应用行为扩展定义文件
return [
    // 应用初始化
    'app_init'     => [
        'app\\common\\behavior\\Common',
    ],
    // 应用开始
    'app_begin'    => [
        'app\\common\\behavior\\Common',
    ],
    // 模块初始化
    'module_init'  => [
        'app\\common\\behavior\\Common',
    ],
    // 表单验证
    'form_validate'  => [
        'app\\common\\behavior\\Common',
    ],
    // 配置数据验证
    'op_data_validate'  => [
        'app\\common\\behavior\\Common',
    ],
    // 钩子数据验证
    'hook_data_validate'  => [
        'app\\common\\behavior\\Common',
    ],
    // 路由数据验证
    'route_data_validate'  => [
        'app\\common\\behavior\\Common',
    ],
    // 导航数据验证
    'nav_data_validate'  => [
        'app\\common\\behavior\\Common',
    ],
    // 队列数据验证
    'term_data_validate'  => [
        'app\\common\\behavior\\Common',
    ],
    // 内容数据验证
    'info_data_validate'  => [
        'app\\common\\behavior\\Common',
    ],
];
