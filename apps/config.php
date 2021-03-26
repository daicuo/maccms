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
    'extra_file_list'   => [THINK_PATH . 'helper' . EXT, APP_PATH . 'helper' . EXT],
    
    'log'   => [
        //file|socket|test
        'type'         => 'test',
        //log|error|notice|info|debug|sql
        'level'        => ['error'],
        //最多只会保留30个
        'max_files'    => 30,
        // error和sql日志单独记录
        'apart_level'  => ['error','sql'],
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
         //去除模板文件里面的html空格与换行
        'strip_space'  => false,
        // 模板编译缓存
        'tpl_cache'    => true,
    ],
        
    //分页配置
    'paginate' => [
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
    
    'session' => [
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
    
     
    'cache' => [
        // 驱动方式
        'type'   => Env::get('cache.type', 'File'),
        // 缓存保存目录
        'path'   => Env::get('cache.path', RUNTIME_PATH.'cache/'),
        // 缓存前缀
        'prefix' => Env::get('cache.prefix', ''),
        // sqlite3缓存数据库
        'db'     => Env::get('cache.db', RUNTIME_PATH.'db/#cache.s3db'),
        // 缓存服务器主机IP
        'host'   => Env::get('cache.host', '127.0.0.1'),
        // 缓存服务器端口
        'port'   => Env::get('cache.port', '6379'),
        // 缓存有效期 0表示永久缓存
        'expire' => Env::get('cache.expire', 0),
        // 缓存有效期 内容
        'expire_detail' => Env::get('cache.expire_detail', ''),
        // 缓存有效期 列表
        'expire_item'   => Env::get('cache.expire_item', ''),
    ],
  
    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',

    // 显示错误信息
    'show_error_msg'         => false,
    
    //默认错误跳转对应的模板文件
    'dispatch_error_tmpl'    => APP_PATH . 'common' . DS . 'view' . DS . 'thinkphp' . DS .'dispatch_jump.tpl',
    
    //默认成功跳转对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH . 'common' . DS . 'view' . DS . 'thinkphp' . DS .'dispatch_jump.tpl',
    
    // 异常页面的模板文件
    'exception_tmpl'         => APP_PATH . 'common' . DS . 'view' . DS . 'thinkphp' . DS .'think_exception.tpl',
    
    //404页面定义 部署模式才生效
    'http_exception_template'    =>  [
        // 定义404错误的重定向页面地址
        404 => APP_PATH . 'common' . DS . 'view' . DS . 'thinkphp' . DS .'http_exception_404.tpl',
        // 还可以定义其它的HTTP status
        500 => APP_PATH . 'common' . DS . 'view' . DS . 'thinkphp' . DS .'http_exception_500.tpl',
    ],
    
    //系统特殊变量
    'daicuo' => [
        'error'   => 'fail',
        'version' => '1.5.4',
    ],
    
    //系统基础配置
    'common' => [
        //插件列表
        'site_applys'      => [],
        //网站名称
        'site_name'        => 'DaiCuo',
        //站点开关
        'site_status'      => 'off',
        //闭站提示
        'site_close'       => 'Site closed',
        //加密字符
        'site_secret'      => 'abcdefghijklmnopqrst',
        //默认电脑端主题
        'site_theme'       => 'default_pc',
        //默认移动端主题
        'wap_theme'        => 'default_wap',
        //别名唯一值附加查询条件
        'where_slug_unique'=> [],
        //表单验证名称
        'validate_name'    => '',
        //表单验证场景
        'validate_scene'   => '',
        //表单验证token
        'validate_token'   => false,
        //上传地址
        'upload_path'      => 'datas/attachment',
        //文件名保存规则
        'upload_save_rule' => 'date',
        //最大可上传大小
        'upload_max_size'  => '2mb',
        //可上传的文件后缀
        'upload_file_ext'  => 'jpg,png,bmp,jpeg,gif,zip,rar,xls,xlsx',
        //可上传的文件类型
        'upload_mime_type' => '',
        //防盗链处理
        'upload_referer'   => '',
        //本地URL接口
        'upload_host'      => '',
        //本地CDN接口
        'upload_cdn'       => '',
        //COOKIE登录时长
        'user_expire'      => 604800,
        //登录错误记录时长限制
        'user_max_expire'  => 3600,
        //最大登录出错次数
        'user_max_error'   => 100,
    ],
    
    //表单元素模板路径
    'form_view' => [
        'hidden'   => APP_PATH.'common/view/form/hidden.tpl',
        'text'     => APP_PATH.'common/view/form/text.tpl',
        'textarea' => APP_PATH.'common/view/form/textarea.tpl',
        'email'    => APP_PATH.'common/view/form/email.tpl',
        'url'      => APP_PATH.'common/view/form/url.tpl',
        'number'   => APP_PATH.'common/view/form/number.tpl',
        'password' => APP_PATH.'common/view/form/password.tpl',
        'json'     => APP_PATH.'common/view/form/json.tpl',
        'radio'    => APP_PATH.'common/view/form/radio.tpl',
        'checkbox' => APP_PATH.'common/view/form/checkbox.tpl',
        'switch'   => APP_PATH.'common/view/form/switch.tpl',
        'select'   => APP_PATH.'common/view/form/select.tpl',
        'custom'   => APP_PATH.'common/view/form/custom.tpl',
        'editor'   => APP_PATH.'common/view/form/editor.tpl',
        'image'    => APP_PATH.'common/view/form/image.tpl',
        'file'     => APP_PATH.'common/view/form/file.tpl',
        'default'  => APP_PATH.'common/view/form/default.tpl'
    ],
    
    //自定义表单字段
    'custom_fields'  => [
        'term_map'   => ['detail_id','term_much_id'],
        'term_much'  => ['term_much_type','term_much_info','term_much_parent','term_much_count'],
        'term_meta'  => ['term_tpl'],
        'info_meta'  => [],
        'user_meta'  => ['user_capabilities','user_expire'],
    ],
    
    //用户角色拥有的权限,数组为用户组,直接定义为权限节点
    'user_roles' => [
        //管理员
        'administrator' => [
            '*',
        ],
        //游客
        'guest'  => [
            'admin/index/login',
            'admin/index/logout',
            'admin/index/token',
        ],
        //扩展节点
        'caps' => [
            'index/index/index',
        ],
    ],
];
