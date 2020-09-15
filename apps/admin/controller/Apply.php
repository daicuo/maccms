<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Apply extends Admin{

	//应用管理
	public function index()
    {
        $event = controller('common/Apply','event');
		$this->assign('applys', $event->appsInfo());
		return $this->fetch();
	}
    
    //卸载应用
    public function delete()
    {
        $event = controller('common/Apply','event');
        if(!$event->uninstall(input('get.module/s'))){
			$this->error(config('daicuo.error'));
		}
        $this->success(lang('success'),'apply/index');
    }
    
    //安装应用
    public function save()
    {
        $event = controller('common/Apply','event');
        if( !$event->install( input('get.module/s') ) ){
			$this->error(config('daicuo.error'));
		}
        $this->success(lang('success'));
    }
	
	//应用市场
	public function store()
    {
		$list = json_decode(DcCurl('auto', 10, lang('appServer').'/store/'),true);
		$this->assign('list', $list);
		return $this->fetch();
	}
    
    //应用打包
	public function update()
    {
        $status = \daicuo\Op::write( input('post.') );
        if( !$status ){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
    
    //应用打包
	public function create()
    {
		return $this->fetch();
	}
	
}