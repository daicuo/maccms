<?php
use think\Env;

return [    
    //调试模式
    'app_debug' => Env::get('app.debug', false),
    
    // 应用Trace
    'app_trace' => Env::get('app.debug', false),
    
    // 默认模块名
    'default_module' => 'home',
    
    // 默认控制器名
    'default_controller' => 'Index',
    
    // 默认操作名
    'default_action' => 'index',    
    
    // 禁止访问模块
    'deny_module_list'  => ['common','admin'],
    
    // URL普通方式参数 用于自动生成
    'url_common_param'  => true,
    
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    //'url_param_type'    =>1,

    //默认后缀
    'url_html_suffix'   => 'html',
    
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'   => 'trim,htmlspecialchars_decode',
    
    // 是否开启路由
    'url_route_on'  =>  true,
    
    // 是否强制路由
    'url_route_must'=>  false,
    
    //域名路由
    'url_domain_deploy' =>  true,

    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    
    'log'   => [
        // 可以临时关闭日志写入
        'type'  => 'test',
        //'level'     => ['error'],//log|error|notice|info|debug|sql
        //'max_files'    => 300,
    ],
  
    // 模板配置
    'template' => [
        // 视图基础目录，配置目录为所有模块的视图起始目录
        //'view_base'    => 'view/',
        // 模板路径
        //'view_path'    => 'view/default/',
        // 模板文件名分隔符
        //'view_depr'    => '-',
        // 模板后缀
        'view_suffix'  => 'tpl',
    ],
        
    //分页配置
    'paginate'               => [
        'type'     => 'page\Bootstrap',
        'var_page' => 'pageNumber',
    ],
    
    'cookie'  => [
        // cookie 名称前缀
        'prefix'    => 'dc_',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],
    
    'session'                       => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'dc_',
        // 驱动方式 支持redis memcachememcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],
    
    /*    
    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => RUNTIME_PATH.'cache/',
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
            // sqlite3缓存数据库
            'db'   => RUNTIME_PATH.'cache.s3db',
        ],                
    */
  
    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',

    // 显示错误信息
    'show_error_msg'         => false,
    
    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl'   => APP_PATH . 'common' . DS . 'view' . DS . 'dispatch_jump.tpl',
    
    //默认成功跳转对应的模板文件
    'dispatch_success_tmpl' => APP_PATH . 'common' . DS . 'view' . DS . 'dispatch_jump.tpl',
    
    // 异常页面的模板文件
    'exception_tmpl'        => APP_PATH . 'common' . DS . 'view' . DS . 'think_exception.tpl',
    
    //404页面定义 部署模式才生效
    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 => APP_PATH . 'common' . DS . 'view' . DS . 'http_exception_404.tpl',
        // 还可以定义其它的HTTP status
        500 => APP_PATH . 'common' . DS . 'view' . DS . 'http_exception_500.tpl',
    ],
    
    //系统特殊变量
    'daicuo' => [
        'error'   =>'fail',
        'version' =>'1.2.15',
    ],
    
    //系统基础配置
    'common' => [
        'site_name'   => 'DaiCuo',
        'site_status' => 'off',
        'site_close'  => 'Site closed',
        'site_secret' => 'abcdefghijklmnopqrst',
        'site_applys' => [],
        'site_theme'  => 'default_pc',
        'wap_theme'   => 'default_wap',
    ],
    
    //自定义字段
    'custom_fields'  => [
        'term_much'  => ['term_much_type','term_much_info','term_much_parent','term_much_count'],
        'term_meta'  => ['term_tpl'],
        'user_meta'  => ['user_capabilities'],
    ],
    
    //用户权限
    'user_roles' => [
        'guest'         => [
            'admin/index/login',
            'admin/index/logout',
            //'index/login/?url=test',
        ],
        'administrator' => [
            '*',
        ]
    ],
];
