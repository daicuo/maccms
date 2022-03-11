<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Config extends Admin
{
    //批量新增与修改配置
	public function update()
    {
        $post = array_merge(['app_debug'=>'off','app_domain'=>'off','site_status'=>'off','site_captcha'=>'off','site_log'=>'off'], input('post.'));
        $result = \daicuo\Op::write($post, 'common', 'config', 'system', 0, 'yes');
		if( !$result ){
		    $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
    
    //快速修改状态
    public function status()
    {
        if( !$ids = input('post.id/a') ){
            $this->error(lang('errorIds'));
        }
        
        $data = [];
        $data['op_status'] = input('request.value/s', 'hidden');
        dbUpdate('common/Op',['op_id'=>['in',$ids]], $data);
        
        $this->success(lang('success'));
    }
    
    //首页配置
    public function index()
    {
        $this->assign('group', model('admin/Config','loglic')->fields());
        
		return $this->fetch();
    }
}