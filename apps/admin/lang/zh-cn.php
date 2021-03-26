<?php
return [
    //后台登录
    'admin_login'                       => '管理员登录',
    //后台顶部
    'admin_home'                        => '后台首页',
    'front_home'                        => '前台首页',
    'admin_logout'                      => '安全退出',
    //左侧菜单
    'menu_config'                       => '设置',
    'menu_system'                       => '系统',
    'menu_apply'                        => '应用',
    'op_index'                          => '全局设置',
    'op_safe'                           => '安全设置',
    'cache_index'                       => '缓存设置',
    'video_index'                       => '视频配置',
    'tool_index'                        => '常规管理',
    'route_index'                       => '路由管理',
    'hook_index'                        => '钩子管理',
    'nav_index'                         => '导航管理',
    'category_index'                    => '分类管理',
    'tag_index'                         => '标签管理',
    'user_index'                        => '用户管理',
    'index_index'                       => '系统环境',
    'apply_index'                       => '应用管理',
    'apply_store'                       => '应用市场',
    'apply_create'                      => '应用打包',
    //设置>>基础配置
    'app_domain'                        => '域名前缀',
    'site_status'                       => '网站开关',
    'site_status_placeholder'           => '站点关闭后将不能访问，后台可正常登录',
    'site_name'                         => '站点名称',
    'site_name_placeholder'             => '给您的网站取个名字',
    'site_domain'                       => '网站主域名',
    'site_domain_placeholder'           => '请填写网站域名，不加http(s)://', 
    'wap_domain'                        => '移动端域名',
    'wap_domain_placeholder'            => '留空则不自动跳转，不加http(s)://',
    'site_theme'                        => '模板主题',
    'site_theme_placeholder'            => '默认模板主题名',
    'wap_theme'                         => '移动端主题',
    'wap_theme_placeholder'             => '移动端默认主题名',        
    'site_icp'                          => '备案信息',
    'site_icp_placeholder'              => '请填写您的网址备案号',
    'site_tongji'                       => '统计代码',
    'site_tongji_placeholder'           => '请填写您的第三方统计代码',
    'site_close'                        => '闭站提示',
    'site_close_placeholder'            => '网站关闭后的温馨提示，后台不受影响',
    'site_id'                           => '联盟渠道',
    'site_id_placeholder'               => '您在呆错官网申请的渠道ID，用于应用的分成与分销等',
    'site_token'                        => '联盟令牌',
    'site_token_placeholder'            => '您在呆错官网申请的令牌，用于插件下载、API数据调用等',
    'site_secret'                       => '系统加密密钥',
    'site_secret_placeholder'           => '写入Cookie时的随机密钥字符串',
    'user_max_expire'                   => '防暴力破解时长',
    'user_max_expire_placeholder'       => '在一定时间内暴力破解检测开关，设为0则不启用，单位：秒',
    'user_max_error'                    => '防暴力破解次数',
    'user_max_error_placeholder'        => '在防暴力破解时长内达到多少次错误的用户，将锁定其IP，阻止其再次尝试',
    'user_force_white'                  => '防暴力白名单IP',
    'user_force_white_placeholder'      => '白名单内的IP不受上述设置所影响，半角(,)逗号分隔',
    //设置>>缓存设置
    'cache_tips'                        => 'File/Sqlite3填写对应的路径、Memcache(d)、Redis填写地址与端口、Wincache、Xcache不需填写',
    'cache_type'                        => '缓存方式',
    'cache_type_option_0'               => 'File文件',
    'cache_type_option_1'               => 'Sqlite3数据库',
    'cache_type_option_2'               => 'Memcache',
    'cache_type_option_3'               => 'Memcached',
    'cache_type_option_4'               => 'Redis',
    'cache_type_option_5'               => 'Wincache',
    'cache_type_option_6'               => 'Xcache',
    'cache_type_option'                 => ['File文件','Sqlite3数据库','Memcache','Memcached','Redis','Wincache','Xcache'],
    'cache_type_placeholder'=>'',
    'cache_prefix'                      => '缓存前缀',
    'cache_prefix_placeholder'          => '自定义缓存前缀，可留空',
    'cache_path'                        => 'File文件缓存目录',
    'cache_path_placeholder'            => 'File文件方式时缓存文件存放的目录，/结尾',
    'cache_db'                          => 'Sqlite3数据库文件',
    'cache_db_placeholder'              => 'Sqlite3方式时的数据库文件',    
    'cache_host'                        => '缓存服务器地址',
    'cache_host_placeholder'            => '填写缓存服务器的IP或域名',    
    'cache_port'                        => '缓存服务器端口',
    'cache_port_placeholder'            => 'Memache(d)默认为11211、Redis默认为6379', 
    'cache_expire'                      => '默认缓存时间',
    'cache_expire_placeholder'          => '默认缓存时间(秒)，0为永久缓存',
    'cache_expire_detail'               => '普通数据缓存时间',
    'cache_expire_detail_placeholder'   => '普通数据缓存时间(秒)，留空不启用缓存，0为永久缓存',
    'cache_expire_item'                 => '循环数据缓存时间',
    'cache_expire_item_placeholder'     => '循环数据缓存时间(秒)，留空不启用缓存，0为永久缓存',
    //系统>>常规管理
    'clear_cache'                       => '清空系统缓存',
    'clear_runtime'                     => '清空系统临时文件', 
    'clear_option'                      => '清空系统配置',
    //系统>>路由管理
    'route_rule'                        => '伪静态结构',
    'route_rule_placeholder'            => '使用/分割，:表式动态变量，[]表式可选变量，$结尾表示完全匹配',
    'route_address'                     => '真实访问路径',
    'route_address_placeholder'         => '如：home/index/index',
    'route_method'                      => '请求类型',
    'route_method_placeholder'          => 'get|post|put|delete|*',
    'route_method_option_0'             => 'GET',
    'route_method_option_1'             => 'POST',
    'route_method_option_2'             => 'PUT',
    'route_method_option_3'             => 'DELETE',
    'route_method_option_4'             => '所有类型',
    'route_option'                      => '路由参数',
    'route_option_placeholder'          => 'Json格式，一般不用填，设置一些路由匹配的条件参数',
    'route_option'                      => '路由参数',
    'route_pattern'                     => '变量规则',
    'route_pattern_placeholder'         => 'Json格式，一般不用填，使用正则表达式弥补动态变量无法限制具体的条件参数',
    //路由验证
    'route_rule_require'                => 'rule%路由表达式必须填写 如：blog/:id',//验证
    'route_rule_number'                 => 'rule%路由表达式不能只为数字',//验证
    'route_address_require'             => 'address%路由地址必须填写 如：home/index/index',
    'route_address_demo'                => 'address%请按"模块/控制器/操作"这样的格式 如：home/index/index',
    'route_option_json'                 => 'option%请按Json格式填写',
    'route_pattern_json'                => 'pattern%请按Json格式填写',
    //系统>>钩子管理
    'hook_name'                         => '钩子名称',
    'hook_name_placeholder'             => 'hook_front_test这样的形式定义',
    'hook_path'                         => '钩子路径',
    'hook_path_placeholder'             => '定义钩子逻辑处理的文件路径，如:app\common\behavior\Common',
    'hook_info'                         => '钩子描述',
    'hook_info_placeholder'             => '对这个钩子的描述信息',
    'hook_overlay'                      => '是否覆盖',
    'hook_overlay_placeholder'          => '采用合并模式，覆盖之前的钩子，默认false',
    //钩子验证
    'hook_name_require'                 => 'hook_name%名称必须填写',
    'hook_name_number'                  => 'hook_name%名称不能全为数字',
    'hook_path_require'                 => 'hook_path%路径必须填写',
    'hook_path_fail'                    => 'hook_path%格式不正确，\\\\分隔',
    'hook_path_none'                    => 'hook_path%未找到处理该钩子的类',
    'hook_path_method'                  => 'hook_path%未找到类的方法，请检查名称',
    'hook_overlay_name'                 => 'hook_overlay%系统内核文件，不允许覆盖',
    //系统>>环境
    'update_to'                         => '建议升级至',
    'frame_name'                        => '框架名称',
    'frame_version'                     => '框架版本',
    'frame_author'                      => '框架作者',
    'server_iformation'                 => '服务器信息',
    'server_environment'                => '服务器环境',
    'web_directory'                     => '网站目录',
    'physical_path'                     => '物理路径',
    'web_domain'                        => '网站域名IP',
    'database_type'                     => '数据库类型',
    'php_version'                       => 'PHP版本',
    'php_engine'                        => 'PHP引擎',
    'email_funtion'                     => '邮件发送需要用到的组件',
];