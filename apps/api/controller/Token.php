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
    
    // 登录界面
    public function index()
    {
        $this->site['path_view'] = 'apps/api/view/token/';
        $this->assign($this->site);
        return $this->fetch();
    }
    
    // 换取Token
    public function login()
    {
        if( DcBool(config('common.site_captcha')) ){
            if(captcha_check(input('request.user_captcha')) == false){
                $this->error( DcError(lang('user_captcha_error')) );
            }
        }
        if( $data = \daicuo\User::token_login(input('request.user_name/s'), input('request.user_pass/s'), config('common.token_expire')) ){
            $this->success( lang('success'), $data );
        }else{
            $this->error( DcError(\daicuo\User::getError()) );
        }
	}
    
    // 更换新TOKEN
    public function update()
    {
        $data = \daicuo\User::token_update($this->site['user']['user_id'], config('common.token_expire'));
        if($data){
            $this->success( lang('success'), $data );
        }
        $this->error( lang('error') );
    }
    
    // 延长TOKEN过期时间
    public function refresh()
    {
        $data = \daicuo\User::token_refresh($this->site['user']['user_token'], config('common.token_expire'));
        if($data){
            $this->success( lang('success'), $data );
        }
        $this->error( lang('error') );
    }
    
    // 删除Token
    public function delete()
    {
        if( \daicuo\User::token_delete($this->site['user']['user_id']) ){
            $this->success( lang('success') );
        }
        $this->error( lang('error') );
    }
}