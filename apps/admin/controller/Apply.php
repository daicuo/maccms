<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Apply extends Admin
{
    // 应用官网
	public function jump()
    {
		$service = new \daicuo\Service();
        $this->redirect($service->apiUrl().'/home/?'.http_build_query($this->query),302);
	}
    
    // 在线升级
    public function upgrade()
    {
        $apply = controller('common/Apply','event');
        if( !$apply->upgradeOnline($this->query['module']) ){
            $error = explode('%',$apply->getError());
            if($error[2]){
                $this->error($error[1].lang($error[0]),$error[2]);
            }else{
                $this->error($error[1].lang($error[0]),'store/index/?searchText='.$error[1]);
            }
        }
        $this->success(lang('success'));
    }
    
    // 在线安装
	public function install()
    {
        $event = controller('common/Apply','event');
        if( !$event->installOnline($this->query) ){
            $error = explode('%',$event->getError());
            if($error[2]){
                $this->error($error[1].lang($error[0]),$error[2]);
            }else{
                $this->error($error[1].lang($error[0]),'store/index/?searchText='.$error[1]);
            }
		}
        $this->success(lang('success'));
	}
    
    // 浏览器下载
    public function down()
    {
        $service = new \daicuo\Service();
        $this->redirect($service->applyDownUrl($this->query),302);
    }
    
    // 禁用插件
    public function disable()
    {
        $event = controller('common/Apply','event');
        if(!$event->updateStatus(input('get.module/s'),'disable')){
			$this->error( lang($event->getError()) );
		}
        $this->success(lang('success'),'apply/index');
    }
    
    // 启用插件
    public function enable()
    {
        $event = controller('common/Apply','event');
        if(!$event->updateStatus(input('get.module/s'),'enable')){
			$this->error( lang($event->getError()) );
		}
        $this->success(lang('success'),'apply/index');
    }
    
    // 卸载插件
    public function remove()
    {
        $event = controller('common/Apply','event');
        if(!$event->uninstall(input('get.module/s'))){
			$this->error( lang($event->getError()) );
		}
        $this->success(lang('success'),'apply/index');
    }
    
    // 应用打包
	public function create()
    {
		return $this->fetch();
	}
    
    // 应用打包保存
    public function save()
    {
        $status = \daicuo\Op::write( input('post.') );
        if( !$status ){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
    }
    
    // 删除应用
    public function delete()
    {
        $event = controller('common/Apply','event');
        if( !$event->uninstall(input('get.module/s'), true) ){
			$this->error( lang($event->getError()) );
		}
        $this->success(lang('success'),'apply/index');
    }
    
    // 本地安装
	public function update()
    {
        $event = controller('common/Apply','event');
        if( !$event->install( input('get.module/s') ) ){
            $error = explode('%',$event->getError());
            if($error[2]){
                $this->error($error[1].lang($error[0]),$error[2]);
            }else{
                $this->error($error[1].lang($error[0]),'store/index/?searchText='.$error[1]);
            }
		}
        $this->success(lang('success'));
	}
    
    //本地应用列表
	public function index()
    {
        $event = controller('common/Apply','event');
        $service = new \daicuo\Service();
		$this->assign('applys', $event->appsInfo());
        $this->assign('api_url', $service->apiUrl());
		return $this->fetch();
	}
	
}