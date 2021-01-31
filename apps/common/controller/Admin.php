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
 * 后台公共控制器
 * @package app\admin\controller
 */
class Admin extends Base
{
    // 系统权限属性
    protected $auth = [
        'check'       => true,
        'rule'        => '',
        'none_login'  => ['admin/index/login','admin/index/logout'],
        'none_right'  => [],
        'error_login' => 'admin/index/login',
        'error_right' => '',
    ];
    
    /**
    * 继承初始化方法
    */
    public function _initialize()
	{
        // 继承上级
        parent::_initialize();
        // 权限验证
        $this->_authCheck();
        // 模板路径
        $this->site['path_view'] = 'apps/admin/view/';
        // 后台钩子
        \think\Hook::listen('hook_admin_init', $this->site);
        // 模板标签
        $this->assign($this->site);
    }
    
    /**
    * 默认新增操作
    * @return mixed
    */
    public function create()
    {
        //config('common.validate_token', true);
        $this->assign('query', $this->query);
        return $this->fetch();
    }
    
    /**
    * 默认修改操作
    * @return mixed
    */
    public function edit()
    {
        $this->assign('query', $this->query);
        return $this->fetch();
    }
    
    /**
    * 默认操作
    * @return mixed
    */
    public function index()
    {
        $this->assign('query', $this->query);
        return $this->fetch();
    }
}