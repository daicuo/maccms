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
    // 操作开始执行
    'action_begin' => [
    ],
    // 视图内容过滤
    'view_filter'  => [            
    ],
    // 应用结束
    'app_end'      => [
    ],
    // 日志写入
    'log_write'    => [        
    ],    
    // 日志写入完成
    'log_write_done'    => [        
    ],    
    // 响应发送
    'response_send'    => [
    ],    
    // 输出结束
    'response_end'    => [        
    ],
];
