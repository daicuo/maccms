<?php
namespace app\admin\loglic;

class Caps
{
    //后台权限节点
    public function back()
    {
        $caps = [
            'admin/index/index',
            'admin/upload/index',
            'admin/video/index',
            'admin/cache/index',
            'admin/config/index',
            //'admin/update/index',
            //'admin/update/online',
            //'admin/union/index',
            'admin/tool/index',
            'admin/apply/index',
            'admin/store/index',
            //'admin/pack/index',
            //'admin/pack/save',
            'admin/user/index',
            'admin/role/index',
            'admin/auth/index',
            'admin/route/index',
            'admin/log/index',
            'admin/field/index',
            'admin/lang/index',
            'admin/navs/index',
            'admin/menu/index',
            'admin/category/index',
            'admin/tag/index',

            'admin/upload/update',
            'admin/video/update',
            'admin/cache/update',
            'admin/config/update',
            'admin/tool/delete',
            'admin/user/save',
            'admin/user/delete',
            'admin/role/save',
            'admin/role/delete',
            'admin/auth/save',
            'admin/auth/delete',
            'admin/route/save',
            'admin/route/delete',
            'admin/log/update',
            'admin/log/delete',
            'admin/field/save',
            'admin/field/delete',
            'admin/lang/save',
            'admin/lang/delete',
            'admin/navs/save',
            'admin/navs/delete',
            'admin/menu/save',
            'admin/menu/delete',
            'admin/category/save',
            'admin/category/delete',
            'admin/tag/save',
            'admin/tag/delete',
        ];
        
        \think\Hook::listen('admin_caps_back', $caps);
        
        return $caps;
    }
    
    //前台权限节点
    public function front()
    {
        $caps = [
            'api/token/update',
            'api/token/refresh',
            'api/token/delete',
            'api/upload/save',
        ];
        
        \think\Hook::listen('admin_caps_front', $caps);
        
        return $caps;
    }
}