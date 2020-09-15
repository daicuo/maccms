<?php
// +----------------------------------------------------------------------
// | DaiCuo框架[基于ThinkPHP5.0开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2019-2020 http://www.daicuo.net
// +----------------------------------------------------------------------
// | DaiCuo承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 老谭 <271513820@qq.com>
// +----------------------------------------------------------------------

namespace app\common\controller;

use app\common\controller\Base;

/**
 * 前台公共控制器
 * @package app\admin\controller
 */
class Front extends Base
{
    /**
    * 继承初始化方法
    */
    public function _initialize()
    {
        // 继承上级
        parent::_initialize();
        // 模板路径
        $this->site['path_view'] = config('template.view_path');
        // 前台钩子
        \think\Hook::listen('hook_front_init', $this->site);
        // 模板标签
        $this->assign($this->site);
	}
}