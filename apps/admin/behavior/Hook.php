<?php
namespace app\admin\behavior;

use think\Controller;

class Hook extends Controller
{
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
    
    // 用户登录后
    public function userLoginAfter(&$data)
    {
    
    }
    
    // 添加用户前
    public function userSaveBefore(&$params)
    {
        /*验证userMeta
        if( is_array($params['data']['user_meta']) ){
            foreach($params['data']['user_meta'] as $key=>$value){
                if( false === DcCheck($value, 'common/UserMeta') ){
                    $this->error(config('daicuo.error'));
                }
            }
        }*/
        //验证user
        if(false === DcCheck($params['data'], 'common/User', 'admin_save')){
			$this->error(config('daicuo.error'));
		}
    }
    
    // 修改用户前
    public function userUpdateBefore(&$params)
    {
        /*验证userMeta
        if( is_array($params['data']['user_meta']) ){
            foreach($params['data']['user_meta'] as $key=>$value){
                if( false === DcCheck($value, 'common/UserMeta') ){
                    $this->error(config('daicuo.error'));
                }
            }
        }*/
        //验证user
        if(false === DcCheck($params['data'], 'common/User', 'admin_update')){
			$this->error(config('daicuo.error'));
		}
    }
    
    // 添加队列前
    public function termSaveBefore(&$params)
    {
        /*验证termMeta
        if( is_array($params['data']['term_meta']) ){
            foreach($params['data']['term_meta'] as $key=>$value){
                if( false === DcCheck($value, 'common/TermMeta') ){
                    $this->error(config('daicuo.error'));
                }
            }
        }*/
    }
    
    // 修改队列前
    public function termUpdateBefore(&$params)
    {
        /*验证termMeta
        if( is_array($params['data']['term_meta']) ){
            foreach($params['data']['term_meta'] as $key=>$value){
                if( false === DcCheck($value, 'common/TermMeta') ){
                    $this->error(config('daicuo.error'));
                }
            }
        }*/
    }
    
    // 后台首页钩子演示
    public function adminIndexHeader(&$params, $extra)
    {
        echo(widget('admin/Common/welcome'));
    }
}