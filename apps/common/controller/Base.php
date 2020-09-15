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

use think\Controller;

use think\Lang;

/**
 * 基类公共控制器
 * @package app\admin\controller
 */
class Base extends Controller
{
    // 系统全局变量
    protected $site = array();
    
    protected $query = [];

    /**
    * 继承初始化方法
    */
    public function _initialize()
    {
        $this->site['user'] = '';
        $this->site['module'] = $this->request->module();
        $this->site['controll'] = strtolower($this->request->controller());
        $this->site['action'] = $this->request->action();
        $this->site['file'] = $this->request->baseFile();
        $this->site['page'] = input('pageNumber/d',1);
        $this->site['path_root'] = ltrim(dirname($this->site['file']), DS).'/';
        $this->query = $this->request->param();
        \think\Hook::listen('hook_base_init', $this->site);
    }
		
    /**
    * 空操作
    * @author 老谭 <271513820@qq.com>
    * @return mixed
    */
    public function _empty($name){
        return abort(404, 'action none');
        //return request()->action();
    }
}