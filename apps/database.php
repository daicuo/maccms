<?php
use think\Env;

return [
    // 数据库类型
    'type'            => Env::get('database.type', 'sqlite'),
    // 数据库名
    'database'        => Env::get('database.database', 'datas/db/#daicuo.db3'),
    // 服务器地址
    'hostname'        => Env::get('database.hostname', '127.0.0.1'),
    // 用户名
    'username'        => Env::get('database.username', 'root'),
    // 密码
    'password'        => Env::get('database.password', 'root'),
    // 端口
    'hostport'        => Env::get('database.hostport', ''),
    // 连接dsn
    'dsn'             => '',
    // 数据库连接参数
    'params'          => [],
    // 数据库编码默认采用utf8
    'charset'         => Env::get('database.charset', 'utf8'),
    // 数据库表前缀
    'prefix'          => Env::get('database.prefix', 'dc_'),
    // 数据库调试模式
    'debug'           => Env::get('database.debug', false),
    // 数据集返回类型
    'resultset_type'  => 'collection',
    // 自动写入时间戳字段
    'auto_timestamp'  => false,
    // 时间字段取出后的默认时间格式
    'datetime_format' => 'Y-m-d H:i:s',
    // 是否需要进行SQL性能分析
    'sql_explain'     => false,
];
