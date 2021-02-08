<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Filter extends Front
{

	public function _initialize()
    {
		parent::_initialize();
	}
    
    //最近更新
	public function lately()
    {
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
    
    //筛选页
    public function _empty($name)
    {
        return DcHtml($name);
    }
    
    //主演页
    public function actor()
    {
        return $this->_field('actor',input('get.id/s','刘德华'));
    }
    
    //导演页
    public function director()
    {
        return $this->_field('director',input('get.id/s','王晶'));
    }
    
    //地区页
    public function area(){
        return $this->_field('area',input('get.id/s','内地'));
    }
    
    //年代页
    public function year()
    {
        return $this->_field('year',input('get.id/s','2020'));
    }
    
    //语言页
    public function language()
    {
        return $this->_field('language',input('get.id/s','国语'));
    }
    
    //按字段获取
    private function _field($field,$value)
    {
        $value = DcHtml($value);
        $args = [];
        $args['limit'] = intval(config('maccms.page_size'));
        $args['pg'] = $this->query['page'];
        $args[$field] = $value;
        $list = apiItem($args);
        $this->assign($this->query);
        $this->assign($list['page']);
        $this->assign('type', $list['type']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        $this->assign('pages',DcPage($list['page']['current_page'], $list['page']['per_page'], $list['page']['total'],
			DcUrl('maccms/filter/'.$field,['id'=>$value,'page'=>'[PAGE]'],'')));
		return $this->fetch();
    }
}