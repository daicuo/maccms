<?php
namespace app\admin\behavior;

use think\Controller;

class Hook extends Controller
{

    // 后台首页
    public function adminIndexHeader(&$params, $extra)
    {
        echo(widget('admin/Common/welcome'));
    }
    
    // 用户登录前
    public function userLoginBefore(&$post)
    {
        if( empty($post['user_name']) ){
            $this->error(lang('user_name').lang('must'));
        }
        if( empty($post['user_pass']) ){
            $this->error(lang('user_pass').lang('must'));
        }
    }
    
}