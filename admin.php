<?php
// 框架系统目录
define('THINK_PATH', __DIR__ .'/thinkphp/');

// 扩展类库目录
define('EXTEND_PATH', __DIR__ .'/extend/');

// 第三方类库目录
define('VENDOR_PATH', __DIR__ .'/vendor/');

// 定义应用目录
define('APP_PATH', __DIR__ . '/apps/');

// 应用运行时缓存目录（必须可写权限）
define('RUNTIME_PATH', __DIR__ .'/datas/');

// 加载框架基础文件
require THINK_PATH . 'base.php';

// 绑定当前入口文件到admin模块
\think\Route::bind('admin');

// 关闭admin模块的路由
\think\App::route(false);

// 执行应用
\think\App::run()->send();