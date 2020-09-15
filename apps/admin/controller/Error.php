<?php
//利用空控制器.空操作.来管理插件
namespace app\admin\controller;

use app\common\controller\Admin;

class Error extends Admin{
	
	//空操作 分层控制器
	public function _empty($name){
		//return action($this->site['controll'].'/Index/'.$name, '', 'admin');
        return $name;
	}
    
    public function index(){
        return 'index';
    }
    
    public function create(){
        return 'index';
    }
    
    public function edit(){
        return 'edit';
    }
	
}