<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Category extends Front{
	protected $cateId = 0;//分类ID
	protected $areaId = 0;
	protected $yearId = 0;
	protected $tagId = 0;
	protected $languageId = 0;
	protected $sortId = 0;//0=最新;1=最多好评;2=最多播放;3=最高评分
	protected $pageIndex = 1;//当前分页
	protected $pageSize = 15;//每页数
	protected $totalRecord = 0;//总记录数
	protected $totalPage = 0;//总页数
	protected $fetchName = '';//模板名称
	
	//继承
	public function _initialize(){
		parent::_initialize();
		$this->pageIndex = input('page/d',1);
	}
    
    //分类ID
    public function index(){
        $id   = input('id/d');
        $page = input('page/d',1);
        $term = categoryId($id);
        //查询已绑定的apiTid
        if($term['term_id']){
            $list = apiItem(['ac'=>'list', 't'=>$term['term_api_tid'], 'pg'=>$page], $term['term_api_url'], $term['term_api_type']);
        }else{
            $term['term_id'] = 0;
            $list = apiItem(['ac'=>'list', 't'=>$id, 'pg'=>$page]);
        }
        $this->assign($term);
        $this->assign($list['page']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        return $this->fetch();
    }
    
    //原始typeId
    public function type(){
        $id   = input('id/d');
        $page = input('page/d',1);
        $info = categoryMeta('term_type_id', $id);
        $this->apiItem($id, $page, $info['term_api']);
        $this->assign($info);
        if($this->request->isAjax()){
            return $this->fetch('ajax');
        }
        return $this->fetch('index');
    }
    
    // 共用
    private function apiItem($typeId, $page, $api=''){
        $list = apiItem(['t'=>$typeId, 'pg'=>$page], $api);
        $this->assign($list['page']);
        $this->assign('item', $list['item']);
    }
    
    public function id(){
        $id = input('id/d');
        $page = input('page/d',1);
        $info = \daicuo\Term::get_id($id);
        //$list = apiItem(['t'=>$info['term_type_id'],'pg'=>$page], $info['term_api']);
        dump($list);
        dump($info);
        dump($info);
        $where = array();
        $where['term_meta_key'] = ['eq','term_type_id'];
        $where['term_meta_value'] = ['eq',$id];

        dump(DcTermMeta($where)->toArray());
        
        $join[0] = ['term','*'];
        $join[1] = ['term_much','*','term_much.term_id=term.term_id'];
        $join[2] = ['term_meta','*','term_meta.term_id=term.term_id'];
        $info = DcGet('common/Term',[
            //'fetchSql' => true,
            //'view'     => $join,
            //'relation'   =>'termMuch,termMeta',
            'with'     => ['termMuch','termMeta'],
            //'where'    => $where,
        ]);
        dump($info->toArray());
        dump($info);
        /*$join[0] = ['term','*'];
        $join[1] = ['term_much','*','term_much.term_id=term.term_id'];
        $join[2] = ['term_meta','*','term_meta.term_id=term.term_id'];
        $model = model('common/Term');
        $list = $model->view($join)->where($where)->fetchSql(false)->find();
        dump($list->toArray());*/
        
        $where = array();
        $where['term_meta_key'] = ['eq','term_type_id'];
        $where['term_meta_value'] = ['eq',$id];
        $model = model('common/term');
        //$info = $model->where($where)->with('term,termMuch')->find();
        $info = $model->hasWhere('termMeta',$where)->with('termMuch,termMeta')->fetchSql(false)->find();
        dump($info->toArray());
        dump($model->getLastSql());
        //dump($info);
        
        
        $model = model('common/termMeta');
        //$info = $model->where($where)->with('term,termMuch')->find();
        $info = $model->where($where)->with('term,termMuch')->find();
        dump($model->getLastSql());
        dump($info->toArray());
        //$info = \daicuo\Term::get_id($id);
        //$list = apiItem(['t'=>$info['term_type_id'],'pg'=>$page], $info['term_api']);
        //dump($list);
        //ump($info);
    }
    
    
	
	//最新
	public function newest(){
		return $this->jsonFiter(0,0,0,0,0,0);
	}
		
	//热门
	public function hotest(){
		return $this->jsonFiter(0,0,0,0,0,2);
	}
	
	//电影
	public function dianying(){
		return $this->jsonFiter(2,0,0,0,0,0);
	}
	
	//电视
	public function dianshi(){
		return $this->jsonFiter(3,0,0,0,0,0);
	}	
	
	//综艺
	public function zongyi(){
		return $this->jsonFiter(4,0,0,0,0,0);
	}
	
	//动漫
	public function dongman(){
		return $this->jsonFiter(5,0,0,0,0,0);
	}

	//港台
	public function gangtai(){
		return $this->jsonFiter(3,'3,6',0,0,0,0);
	}
	
	//美剧
	public function meiju(){
		return $this->jsonFiter(3,8,0,0,0,0);
	}
	
	//英剧
	public function yingju(){
		return $this->jsonFiter(3,13,0,0,0,0);
		return $this->fetch();
	}	
	
	//泰剧
	public function taiju(){
		return $this->jsonFiter(3,9,0,0,0,0);
	}
	
	//日剧
	public function riju(){
		return $this->jsonFiter(3,7,0,0,0,0);
		return $this->fetch();
	}
	
	//韩剧
	public function hanju(){
		return $this->jsonFiter(3,5,0,0,0,0);
	}
	
	//地区
	public function area(){
		$this->fetchName = 'index';
		return $this->jsonFiter(0,input('id/d',0),0,0,0,0,input('id/d',0));
	}
	
	//年代
	public function year(){
		$this->fetchName = 'index';
		return $this->jsonFiter(0,0,input('id/d',0),0,0,0,input('id/d',0));
	}
	
	//TAG
	public function tag(){
		$this->fetchName = 'index';
		return $this->jsonFiter(0,0,0,input('id/d',0),0,0,input('id/d',0));
	}
	
	//语言
	public function language(){
		$this->fetchName = 'index';
		return $this->jsonFiter(0,0,0,0,input('id/d',0),0,input('id/d',0));
	}
	
	//排序
	public function sort(){
		$this->fetchName = 'index';
		return $this->jsonFiter(0,0,0,0,0,input('id/d',0),input('id/d',0));
	}
}