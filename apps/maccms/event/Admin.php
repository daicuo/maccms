<?php
namespace app\maccms\event;

use think\Controller;

/*
** MacCms管理后台首页
*/

class Admin extends Controller
{
	
	public function _initialize(){
		parent::_initialize();
	}
    
    //管理首页
	public function index(){
        $themes = array();
        foreach( glob_basename('apps/maccms/theme/') as $value){
            $themes[$value] = $value;
        }
        $this->assign('themes', $themes);
        return $this->fetch('maccms@admin/index');
	}
    
    //保存配置
    public function update(){
        $status = \daicuo\Op::write(
            input('post.'),
            input('module/s','maccms'), 
            input('controll/s'),
            input('action/s'),
            input('order/d',0),
            input('autoload/s','yes')
        );
		if( !$status ){
		    $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
}