<?php
namespace app\admin\controller;

use app\common\controller\Admin;

/**
 * 配置管理
 */
class Op extends Admin
{
	
	//更新基础配置
	public function update()
    {
        $status = \daicuo\Op::write(
            array_merge( ['app_debug'=>'off','app_domain'=>'off','site_status'=>'off'], input('post.') ),
            input('module/s','common'), 
            input('controll/s'),
            input('action/s'),
            input('order/d',0),
            input('autoload/d','yes')
        );
		if( !$status ){
		    $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
	
}
