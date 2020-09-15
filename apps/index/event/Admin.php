<?php
namespace app\index\event;

use think\Controller;

class Admin extends Controller
{
	
	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
        $themes = array();
        foreach( glob_basename('apps/index/theme/') as $value){
            $themes[$value] = $value;
        }
        $this->assign('themes', $themes);
        return $this->fetch('index@admin/index');
	}
    
    public function update(){
        $status = \daicuo\Op::write(
            input('post.'),
            input('module/s','index'), 
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