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
	public function index()
    {
        $list = apiItem([
            'limit' => intval(config('maccms.page_size')),
            'pg'    => $this->site['page'],
            'order' => 'addtime',
            'sort'  => 'desc',
        ]);
        $this->assign($list['page']);
        $this->assign('type', $list['type']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        $this->assign('pages',DcPage(
            $list['page']['current_page'], 
            $list['page']['per_page'], 
            $list['page']['total'],
			DcUrl('maccms/filter/index',['pageNumber'=>'[PAGE]'])
        ));
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
        return $this->_field('actor',input('get.wd/s','刘德华'));
    }
    
    //导演页
    public function director()
    {
        return $this->_field('director',input('get.wd/s','王晶'));
    }
    
    //地区页
    public function area(){
        return $this->_field('area',input('get.wd/s','内地'));
    }
    
    //年代页
    public function year()
    {
        return $this->_field('year',input('get.wd/s','2022'));
    }
    
    //语言页
    public function language()
    {
        return $this->_field('language',input('get.wd/s','国语'));
    }
    
    //按字段获取
    private function _field($field='index', $value='')
    {
        $value = DcHtml(urldecode($value));
        //API参数
        $args = [];
        $args['limit'] = intval(config('maccms.page_size'));
        $args['pg']   = $this->site['page'];
        $args[$field] = $value;
        //调用数据
        $list = apiItem($args);
        //模板变量
        $this->assign($this->query);
        $this->assign($list['page']);
        $this->assign('type', $list['type']);
        $this->assign('item', $list['item']);
        //AJAX模板
        if($this->request->isAjax()){
            if($this->site['page'] > $list['page']['last_page']){
                return null;
            }
            return $this->fetch('ajax');
        }
        //普通模板
        $this->assign('pages',DcPage(
            $list['page']['current_page'], 
            $list['page']['per_page'], 
            $list['page']['total'],
			DcUrl('maccms/filter/'.$field,['wd'=>$value,'pageNumber'=>'[PAGE]'])
        ));
		return $this->fetch();
    }
}