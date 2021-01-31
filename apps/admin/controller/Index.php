<?php
namespace app\admin\controller;

use app\common\controller\Admin;

/**
 * 后台首页
 * @package app\admin\controller
 */
class Index extends Admin
{
    /**
    * 后台首页
    * @return mixed
    */
    public function index()
    {
        $service = new \daicuo\Service();
        
        $this->assign('api_url', $service->apiUrl());
        
        return $this->fetch();
    }

    /**
    * 管理员登录
    * @return mixed
    */
    public function login()
    {
        if( $this->request->isPost() ){
        
            if(captcha_check(input('post.user_captcha')) == false){
                $this->error(lang('user_captcha_error'));
            }
            
            if(\daicuo\User::login() == false){
                $this->error(config('daicuo.error'));
            }
            
            $this->redirect('index/index', '', '');
        }
        
        if($this->site['user']['user_id']){
        
            $this->redirect('index/index', '', '');
        }
        
        $service = new \daicuo\Service();
        
        $this->assign('api_url', $service->apiUrl());
        
        return $this->fetch();
    }

    /**
    * 管理员退出
    * @return mixed
    */
    public function logout()
    {
        \daicuo\User::logout();
        
        $this->success(lang('logout').lang('success'), 'index/login');
    }
}
