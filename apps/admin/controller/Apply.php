<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Apply extends Admin
{
    // 在线升级
    public function upgrade()
    {
        $apply = new \daicuo\Apply();
        if( !$apply->upgradeOnline($this->query['module']) ){
            $error = explode('%',$apply->getError());
            $this->error(lang($error[0]).$error[2],$error[1]);
        }
        $this->success(lang('success'));
    }
    
    // 在线安装
    public function install()
    {
        $apply = new \daicuo\Apply();
        if( !$apply->installOnline($this->query['module']) ){
            $error = explode('%',$apply->getError());
            $this->error(lang($error[0]).$error[2],$error[1]);
		    }
        $this->success(lang('success'));
    }
    
    // 浏览器打开应用官网
    public function jump()
    {
        $service = new \daicuo\Service();
        $this->redirect($service->apiUrl().'/home/?'.http_build_query($this->query),302);
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
        $apply = new \daicuo\Apply();
        if(!$apply->updateStatus(input('get.module/s'),'disable')){
          $this->error( lang($apply->getError()) );
        }
        $this->success(lang('success'),'apply/index');
    }
    
    // 启用插件
    public function enable()
    {
        $apply = new \daicuo\Apply();
        if(!$apply->updateStatus(input('get.module/s'),'enable')){
          $this->error( lang($apply->getError()) );
        }
        $this->success(lang('success'),'apply/index');
    }
    
    // 卸载插件
    public function remove()
    {
        $apply = new \daicuo\Apply();
        if(!$apply->remove(input('get.module/s'))){
          $this->error( lang($apply->getError()) );
        }
        $this->success(lang('success'),'apply/index');
    }
    
    // 应用手动安装
    public function save()
    {
        $apply = new \daicuo\Apply();
        if( !$apply->install(input('get.module/s'),'install') ){
            $error = explode('%',$apply->getError());
            $this->error(lang($error[0]).$error[2],$error[1]);
        }
        $this->success(lang('success'));
    }
    
    // 应用手动删除
    public function delete()
    {
        $apply = new \daicuo\Apply();
        if( !$apply->uninstall(input('get.module/s')) ){
          $this->error( lang($apply->getError()) );
        }
        $this->success(lang('success'),'apply/index');
    }
    
    // 应用手动升级
    public function update()
    {
        $apply = new \daicuo\Apply();
        if( !$apply->install(input('get.module/s'), 'upgrade') ){
            $error = explode('%',$apply->getError());
            $this->error(lang($error[0]).$error[2],$error[1]);
        }
        $this->success(lang('success'));
    }
    
    //本地应用列表
    public function index()
    {
        \daicuo\Apply::appsCheck();//同步安装记录
        $this->assign('applys',  \daicuo\Apply::appsInfo());
        $this->assign('api_url', \daicuo\Service::apiUrl());
        return $this->fetch();
    }
}