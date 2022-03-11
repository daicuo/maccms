<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Addon extends Admin
{
	//插件管理入口
    public function index()
    {
        //插件参数必须
        if( (empty($this->query['module'])) || (empty($this->query['controll'])) || (empty($this->query['action'])) ){
            $this->error(lang('mustIn'));
        }
        //过滤非法参数
        $module   = DcHtml($this->query['module']);
        $controll = DcHtml($this->query['controll']);
        $action   = DcHtml($this->query['action']);
        //后台插件模板变量
        $this->assign('path_addon', 'apps/'.$module.'/');
        //调用插件模块、控制器、操作
        return action($module.'/'.ucfirst($controll).'/'.$action, '', 'event');
	}
    
    //空操作
	public function _empty($name)
    {
        return $this->index();
	}
}