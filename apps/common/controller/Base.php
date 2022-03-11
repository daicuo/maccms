<?php
namespace app\common\controller;

use think\Controller;

/**
 * 基类公共控制器
 * @package app\admin\controller
 */
class Base extends Controller
{
    // 系统全局变量
    protected $site = [];
    
    // 地址栏参数
    protected $query = [];
    
    // 系统权限属性
    protected $auth = [
        'check'       => false,
        'rule'        => '',
        'none_login'  => [],
        'none_right'  => [],
        'error_login' => 'index/index/index',
        'error_right' => '',
    ];

    /**
     * 继承初始化方法
     */
    protected function _initialize()
    {
        header("Content-type:text/html;charset=utf-8");
        $this->site['user'] = \daicuo\User::get_current_user();
        $this->site['module'] = $this->request->module();
        $this->site['controll'] = strtolower($this->request->controller());
        $this->site['action'] = $this->request->action();
        $this->site['domain'] = $this->request->domain();
        $this->site['file'] = $this->request->baseFile();
        $this->site['page'] = input('pageNumber/d', 1);
        $this->site['path_root'] = ltrim(dirname($this->site['file']), DS).'/';
        $this->site['path_upload'] = DcUrlAdmin('api/upload/save', [], '');
        $this->site['path_view'] = '';
        // 地址栏参数
        $this->query = $this->request->param();
        // 系统初始化钩子
        \think\Hook::listen('hook_base_init', $this->site);
    }
    
    /**
     * 权限验证方法 默认都需要登录 都需要鉴权 白名单除外
     * @author 老谭 <271513820@qq.com>
     * @return mixed
     */
    protected function _authCheck()
    {
        //认证开关
        if( false == $this->auth['check'] ){
            return true;
        }
        //特殊权限
        if($this->auth['none_login'] == '*'){
            return true;
        }
        //权限规则
        if( empty($this->auth['rule']) ){
            $this->auth['rule'] = $this->site['module'].'/'.$this->site['controll'].'/'.$this->site['action'];
        }
        //白名单验证
        if( !in_array($this->auth['rule'], $this->auth['none_login']) ){
            // 此操作不在白名单内验证登录
            if($this->site['user']['user_id'] < 1){
                $this->error( DcError(lang('user_login_error')), $this->auth['error_login']);
            }
            // 鉴权特殊权限验证
            if($this->auth['none_right'] == '*'){
                return true;
            }
            // 不需要鉴权的白名单里没有此规则需验证是否有对应权限关系
            if( !in_array($this->auth['rule'], $this->auth['none_right']) ){
                if ( false == \daicuo\Auth::check($this->auth['rule'], $this->site['user']['user_capabilities'], $this->site['user']['user_caps']) ) {
                    $this->error( DcError(lang('user_capabilities_error')), $this->auth['error_right']);
                }
            }
        }
    }

    /**
     * 空操作
     * @author 老谭 <271513820@qq.com>
     * @return mixed
     */
    public function _empty($name)
    {
        return abort(404, 'action none');
    }
}