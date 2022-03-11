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
    ]
];
