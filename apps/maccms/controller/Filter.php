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
        $list = apiItem(['limit'=>intval(config('maccms.page_size')), 'pg'=>$this->query['page']]);
        $this->assign($this->query);
        $this->assign($list['page']);
        $this->assign('type', $list['type']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        $this->assign('pages',DcPage($list['page']['current_page'], $list['page']['per_page'], $list['page']['total'],
			DcUrl('maccms/filter/lately',['page'=>'[PAGE]'],'')));
		return $this->fetch();
	}
    
}