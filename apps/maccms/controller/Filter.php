<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Filter extends Front
{

	public function _initialize(){
		parent::_initialize();
	}
    
    //最近更新
	public function lately(){
        $list = apiItem(['pg'=>$this->query['page']]);
        $this->assign($this->query);
        $this->assign($list['page']);
        $this->assign('type', $list['type']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
		return $this->fetch();
	}
    
}