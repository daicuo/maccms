<?php
// 框架系统目录
define('THINK_PATH', __DIR__ .'/thinkphp/');

// 扩展类库目录
define('EXTEND_PATH', __DIR__ .'/extend/');

// 第三方类库目录
define('VENDOR_PATH', __DIR__ .'/vendor/');

// 定义应用目录
define('APP_PATH', __DIR__ . '/apps/');

// 配置目录
//define('CONF_PATH', __DIR__ .'/datas/config/');

// 应用运行时缓存目录（必须可写权限）
define('RUNTIME_PATH', __DIR__ .'/datas/');

// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';