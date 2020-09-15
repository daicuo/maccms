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
    public function index(){
        return $this->fetch();
    }

    /**
    * 管理员登录
    * @return mixed
    */
    public function login(){
        if( $this->request->isPost() ){
            if(\daicuo\User::login() == false){
                $this->error(config('daicuo.error'));
            }
            $this->success(lang('login').lang('success'),'index/index');
        }
        return $this->fetch();
    }

    /**
    * 管理员退出
    * @return mixed
    */
    public function logout(){
        \daicuo\User::logout();
        $this->success(lang('logout').lang('success'), 'index/login');
    }
}
