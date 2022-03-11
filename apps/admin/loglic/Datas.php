<?php
namespace app\admin\loglic;

class Datas
{
    //公共初始配置
    public function defaultConfig()
    {
        return model('common/Config','loglic')->install([
            'site_status'     => 'on',
            'app_debug'       => 'on',
            'app_domain'      => 'off',
            'site_captcha'    => 'off',
            'editor_name'     => 'textarea',
            'site_name'       => '呆错后台管理框架',
            'site_tongji'     => '<script src="https://cdn.daicuo.cc/51la/15321648.js"></script>',
            'site_secret'     => uniqid(),
            'site_log'        => 'delete',
            'token_expire'    => 1,
            'user_max_expire' => 0,
            'user_max_error'  => 0,
            'video_in'        => 'on',
            'video_size'      => '16by9',
        ], 'common');
    }
    
    //安装动态语言包
    public function defaultLang()
    {
        return model('common/Lang','loglic')->install([
            'error404'  => '页面不存在或已删除',
            'error500'  => '系统发生错误',
            'errorIds'  => '请选择ID',
        ],'admin','zh-cn');
    }
    
    //安装用户组
    public function defaultRole()
    {
        return model('common/Role','loglic')->install([
            [
                'op_name'     => 'subscriber',
                'op_value'    => '订阅者',
                'op_module'   => 'admin',
            ],
            [
                'op_name'     => 'contributor',
                'op_value'    => '投稿者',
                'op_module'   => 'admin',
            ]
        ]);
    }
    
    //安装初始权限
    public function defaultAuth()
    {
        return model('common/Auth','loglic')->install([
            [
                'op_name'     => 'subscriber',
                'op_value'    => 'api/token/update',
                'op_module'   => 'admin',
                'op_action'   => 'front',
            ],
            [
                'op_name'     => 'subscriber',
                'op_value'    => 'api/token/refresh',
                'op_module'   => 'admin',
                'op_action'   => 'front',
            ],
            [
                'op_name'     => 'subscriber',
                'op_value'    => 'api/token/delete',
                'op_module'   => 'admin',
                'op_action'   => 'front',
            ],
            [
                'op_name'     => 'contributor',
                'op_value'    => 'api/token/update',
                'op_module'   => 'admin',
                'op_action'   => 'front',
            ],
            [
                'op_name'     => 'contributor',
                'op_value'    => 'api/token/refresh',
                'op_module'   => 'admin',
                'op_action'   => 'front',
            ],
            [
                'op_name'     => 'contributor',
                'op_value'    => 'api/token/delete',
                'op_module'   => 'admin',
                'op_action'   => 'front',
            ],
            [
                'op_name'     => 'contributor',
                'op_value'    => 'api/upload/save',
                'op_module'   => 'admin',
                'op_action'   => 'front',
            ]
        ]);
    }
    
    //安装后台菜单
    public function defaultMenu()
    {
        $this->menuFather();
        
        $this->menuSon();
        
        return true;
    }
    
    //一级菜单
    private function menuFather()
    {
        return model('common/Menu','loglic')->install([
            [
                'term_name'     => '后台首页',
                'term_slug'     => 'admin/index/index',
                'term_info'     => 'fa-gear',
                'term_action'   => 'top',
                'term_module'   => 'admin',
                'term_order'    => '1',
            ],
            [
                'term_name'     => '前台首页',
                'term_slug'     => '../../',
                'term_info'     => 'fa-home',
                'term_type'     => '_blank',
                'term_action'   => 'top',
                'term_module'   => 'admin',
                'term_order'    => '1',
            ],
            [
                'term_name'     => '安全退出',
                'term_slug'     => 'admin/index/logout',
                'term_info'     => 'fa-sign-out',
                'term_action'   => 'top',
                'term_module'   => 'admin',
                'term_order'    => '1',
            ],
            [
                'term_name'     => '设置',
                'term_slug'     => 'config',
                'term_info'     => 'fa-gears',
                'term_action'   => 'left',
                'term_module'   => 'admin',
                'term_order'    => '99',
            ],
            [
                'term_name'     => '用户',
                'term_slug'     => 'user',
                'term_info'     => 'fa-users',
                'term_action'   => 'left',
                'term_module'   => 'admin',
                'term_order'    => '-7',
            ],
            [
                'term_name'     => '应用',
                'term_slug'     => 'apply',
                'term_info'     => 'fa-rocket',
                'term_action'   => 'left',
                'term_module'   => 'admin',
                'term_order'    => '-8',
            ],
            [
                'term_name'     => '系统',
                'term_slug'     => 'system',
                'term_info'     => 'fa-dashboard',
                'term_action'   => 'left',
                'term_module'   => 'admin',
                'term_order'    => '-9',
            ],
        ]);
    }
    
    //批量添加二级菜单
    private function menuSon()
    {
        //添加左侧二级菜单
        $result = model('common/Menu','loglic')->install([
            [
                'term_name'   => '全局设置',
                'term_slug'   => 'admin/config/index',
                'term_info'   => 'fa-gear',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '9',
            ],
            [
                'term_name'   => '缓存设置',
                'term_slug'   => 'admin/cache/index',
                'term_info'   => 'fa-folder',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '8',
            ],
            [
                'term_name'   => '上传设置',
                'term_slug'   => 'admin/upload/index',
                'term_info'   => 'fa-upload',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '7',
            ],
            [
                'term_name'   => '视频设置',
                'term_slug'   => 'admin/video/index',
                'term_info'   => 'fa-file-movie-o',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '6',
            ],
        ],'设置');
        
        //添加左侧二级菜单
        $result = model('common/Menu','loglic')->install([
            [
                'term_name'   => '用户管理',
                'term_slug'   => 'admin/user/index',
                'term_info'   => 'fa-user',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '9',
            ],
            [
                'term_name'   => '角色管理',
                'term_slug'   => 'admin/role/index',
                'term_info'   => 'fa-paw',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '8',
            ],
            [
                'term_name'   => '权限管理',
                'term_slug'   => 'admin/auth/index',
                'term_info'   => 'fa-diamond',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '7',
            ],
        ],'用户');
        
        //添加左侧二级菜单
        $result = model('common/Menu','loglic')->install([
            [
                'term_name'   => '应用市场',
                'term_slug'   => 'admin/store/index',
                'term_info'   => 'fa-cloud',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '9',
            ],
            [
                'term_name'   => '应用管理',
                'term_slug'   => 'admin/apply/index',
                'term_info'   => 'fa-archive',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '8',
            ],
            [
                'term_name'   => '应用打包',
                'term_slug'   => 'admin/pack/index',
                'term_info'   => 'fa-check',
                'term_status' => 'hidden',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '7',
            ],
        ],'应用');
        
        //添加左侧二级菜单
        $result = model('common/Menu','loglic')->install([
            [
                'term_name'   => '工具维护',
                'term_slug'   => 'admin/tool/index',
                'term_info'   => 'fa-wrench',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '9',
            ],
            [
                'term_name'   => '前台菜单',
                'term_slug'   => 'admin/navs/index',
                'term_info'   => 'fa-sitemap',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '8',
            ],
            [
                'term_name'   => '前台权限',
                'term_slug'   => 'admin/caps/front',
                'term_info'   => 'fa-gavel',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '7',
            ],
            [
                'term_name'   => '路由管理',
                'term_slug'   => 'admin/route/index',
                'term_info'   => 'fa-wifi',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '6',
            ],
            [
                'term_name'   => '日志管理',
                'term_slug'   => 'admin/log/index',
                'term_info'   => 'fa-history',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '5',
            ],
            [
                'term_name'   => '语言定义',
                'term_slug'   => 'admin/lang/index',
                'term_info'   => 'fa-commenting',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '4',
            ],
            [
                'term_name'   => '后台菜单',
                'term_slug'   => 'admin/menu/index',
                'term_info'   => 'fa-navicon',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '3',
            ],
            [
                'term_name'   => '后台权限',
                'term_slug'   => 'admin/caps/index',
                'term_info'   => 'fa-ban',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '2',
            ],
            [
                'term_name'   => '系统环境',
                'term_slug'   => 'admin/index/index',
                'term_info'   => 'fa-clone',
                'term_action' => 'left',
                'term_module' => 'admin',
                'term_order'  => '-9',
            ],
        ],'系统');
        
        return $result;
    }

}