<?php
namespace app\api\controller;

use app\common\controller\Api;

class Token extends Api
{
    // 初始化
	public function _initialize()
    {
        // 不需要登录的操作
        $this->auth['none_login'] = ['api/token/index','api/token/login'];
        // 继承上级
		parent::_initialize();
	}
    
    /**
    * 获取Token
    * @return mixed
    */
    public function index()
    {
        return $this->fetch();
    }
    
    /**
     * 换取Token
     * @return array
     */
    public function login()
    {
        if(captcha_check(input('post.user_captcha')) == false){
            $this->error( DcError(lang('user_captcha_error')) );
        }
        if( $data = \daicuo\User::token_login(input('post.user_name/s'), input('post.user_pass/s')) ){
            $this->success( lang('success'), $data );
        }else{
            $this->error( DcError(\daicuo\User::getError()) );
        }
	}
    
    /**
     * 刷新授权时间
     * @return array 刷新后的token信息
     */
    public function refresh()
    {
        $data = \daicuo\User::token_update($this->site['user']['user_id'], $this->site['user']['user_token']);
        if($data){
            $this->success( lang('success'), $data );
        }
        $this->error( lang('error') );
    }
    
    /**
     * 删除Token
     * @return array
     */
    public function delete()
    {
        if( \daicuo\User::token_delete($this->site['user']['user_id']) ){
            $this->success( lang('success') );
        }
        $this->error( lang('error') );
    }
}