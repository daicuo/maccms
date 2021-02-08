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
    
    //过滤配置
	public function filter(){
        return $this->fetch('maccms@admin/filter');
	}
    
    //首页轮播
	public function slite(){
        return $this->fetch('maccms@admin/slite');
	}

    //链接管理
	public function link(){
        return $this->fetch('maccms@admin/link');
	}
    
    //广告配置
	public function poster(){
        return $this->fetch('maccms@admin/poster');
	}
    
    //广告配置
	public function posterwap(){
        return $this->fetch('maccms@admin/posterwap');
	}
    
    //微信平台
	public function weixin(){
        return $this->fetch('maccms@admin/weixin');
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