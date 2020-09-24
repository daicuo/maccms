<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Addon extends Admin
{
	//插件管理入口
    public function index()
    {
        //插件参数必须
        if( (empty($this->query['module'])) || (empty($this->query['controll'])) || (empty($this->query['action'])) ){
            $this->error(lang('mustIn'));
        }
        //定义插件路径
        $this->site['path_addon'] = 'apps/'.Dchtml($this->query['module']).'/';
        //加载插件默认模块配置
        DcLoadConfig(APP_PATH.Dchtml($this->query['module']).'/config.php');
        //注册插件默认钩子
        
        // 初始化后台钩子
        \think\Hook::listen('hook_admin_init', $this->site);
        // 后台插件模板变量
        $this->assign('path_addon', $this->site['path_addon']);
        //调用插件模块、控制器、操作
        return action(Dchtml($this->query['module']).'/'.ucfirst(Dchtml($this->query['controll'])).'/'.Dchtml($this->query['action']), '', 'event');
	}
    
    //空操作
	public function _empty(){
        return $this->index();
	}
	
}