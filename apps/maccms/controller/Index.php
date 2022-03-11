<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Index extends Front
{
	public function _initialize()
    {
		parent::_initialize();
	}

	public function index()
    {
        //首页最新
        if(config('maccms.limit_index')){
            $news = apiNew( intval(config('maccms.limit_index')) );
        }
        //首页分类
        $categorys = categoryItem([
            'limit' => intval(config('maccms.limit_categorys'))
        ]);
        //模板变量
        $this->assign('news', $news);
        $this->assign('categorys', $categorys);
        //加载模板
		return $this->fetch();
	}
}