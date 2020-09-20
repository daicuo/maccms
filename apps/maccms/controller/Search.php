<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Search extends Front{
	
	//继承
	public function _initialize(){
		parent::_initialize();
    }
    
    //空操作
	public function _empty($name){
        $list = apiItem(['wd'=>$this->query['wd'],'pg'=>$this->query['page']]);
        $this->assign($this->query);
        $this->assign($list['page']);
        $this->assign('type', $list['type']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        return $this->fetch('index');
	}
    
}