<?php
namespace app\common\controller;

use app\common\controller\Base;

use think\exception\HttpResponseException;

use think\exception\ValidateException;

/**
 * Api公共控制器
 * @package app\admin\controller
 */
class Api extends Base
{
    // 系统权限属性
    protected $auth = [
        'check'       => true,
        'rule'        => '',
        'none_login'  => ['api/index/index'],
        'none_right'  => [],
        'error_login' => '',
        'error_right' => '',
    ];
    
    // 默认响应输出类型,支持json/xml/jsonp
    protected $responseType = 'json';
    
    // 继承初始化方法
    public function _initialize()
    {
        // 继承上级
        parent::_initialize();
        // 权限验证
        $this->_authCheck();
        // API钩子
        \think\Hook::listen('hook_api_init', $this->site);
	}
    
    /**
     * 权限验证方法 默认都需要登录 都需要鉴权 白名单除外
     * @author 老谭 <271513820@qq.com>
     * @return mixed
     */
    public function _authCheck(){
        // 认证开关
        if( false == $this->auth['check'] ){
            return true;
        }
        // 登录特殊权限
        if($this->auth['none_login'] == '*'){
            return true;
        }
        // 权限规则
        if( empty($this->auth['rule']) ){
            $this->auth['rule'] = $this->site['module'].'/'.$this->site['controll'].'/'.$this->site['action'];
        }
        // 白名单机制
        if( !in_array($this->auth['rule'], $this->auth['none_login']) ){
            // Cookie获取不到登录用户信息
            if($this->site['user']['user_id'] < 1){
                // 通过token信息获取用户信息
                $this->site['user'] = \daicuo\User::token_current_user();
                // Cookie+Token均验证失败时
                if($this->site['user']['user_id'] < 1){
                    $this->error( DcError(\daicuo\User::getError()), null , 0);//401
                }
            }
            // 权限特殊权限
            if($this->auth['none_right'] == '*'){
                return true;
            }
            // 不需要鉴权的白名单里没有此规则需要验证是否有对应权限关系
            if( !in_array($this->auth['rule'], $this->auth['none_right']) ){
                if ( false == \daicuo\Auth::check($this->auth['rule'], $this->site['user']['user_capabilities'], $this->site['user']['user_caps']) ) {
                    $this->error( DcError(lang('user_capabilities_error')), null, 0);//403
                }
            }
        }
    }
    
    /**
     * 操作成功返回的数据
     * @param string $msg    提示信息
     * @param mixed  $data   要返回的数据
     * @param int    $code   错误码，默认为1
     * @param string $type   输出类型
     * @param array  $header 发送的 Header 信息
     */
    protected function success($msg = '', $data = null, $code = 1, $type = null, array $header = [])
    {
        $this->result($msg, $data, $code, $type, $header);
    }

    /**
     * 操作失败返回的数据
     * @param string $msg    提示信息
     * @param mixed  $data   要返回的数据
     * @param int    $code   错误码，默认为0
     * @param string $type   输出类型
     * @param array  $header 发送的 Header 信息
     */
    protected function error($msg = '', $data = null, $code = 0, $type = null, array $header = [])
    {
        $this->result($msg, $data, $code, $type, $header);
    }

    /**
     * 返回封装后的 API 数据到客户端
     * @access protected
     * @param mixed  $msg    提示信息
     * @param mixed  $data   要返回的数据
     * @param int    $code   错误码，默认为0
     * @param string $type   输出类型，支持json/xml/jsonp
     * @param array  $header 发送的 Header 信息
     * @return void
     * @throws HttpResponseException
     */
    protected function result($msg, $data = null, $code = 0, $type = null, array $header = [])
    {
        $result = [
            'code' => $code,
            'msg'  => $msg,
            'time' => \think\Request::instance()->server('REQUEST_TIME'),
            'data' => $data,
        ];
        
        // 如果未设置类型则自动判断
        $type = $type ? $type : ($this->request->param(config('var_jsonp_handler')) ? 'jsonp' : $this->responseType);

        if (isset($header['statuscode'])) {
            $code = $header['statuscode'];
            unset($header['statuscode']);
        } else {
            //未设置状态码,根据code值判断
            $code = $code >= 1000 || $code < 200 ? 200 : $code;
        }
        
        $response = \think\Response::create($result, $type, $code)->header($header);
        
        throw new HttpResponseException($response);
    }
}