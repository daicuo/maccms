<?php
namespace app\common\controller;

use app\common\controller\Base;

/**
 * 前台公共控制器
 * @package app\admin\controller
 */
class Front extends Base
{
    // 继承初始化方法
    public function _initialize()
    {
        // 继承上级
        parent::_initialize();
        // 权限验证
        $this->_authCheck();
        // 模板路径
        $this->site['path_view'] = config('template.view_path');
        // 前台钩子
        \think\Hook::listen('hook_front_init', $this->site);
        // 模板标签
        $this->assign($this->site);
	}
}