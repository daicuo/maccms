<?php
namespace app\admin\controller;

use app\common\controller\Admin;

/**
 * 导航管理
 */
class Nav extends Admin{

    /*
    protected $beforeActionList = [
        'postData'  =>  ['only'=>'save,update'],
        'second' =>  ['except'=>'create'],
    ];
    */

	public function index(){
        if($this->request->isAjax()){
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_status';
            $args['sort']  = input('get.sortName/s','op_order');
            $args['order'] = input('get.sortOrder/s','asc');
            $args['tree']  = true;
            //$args['fetchSql'] = true;
            //$args['limit'] = 0;
            //$args['page'] = 0;
            if($this->query['op_module']){
                $args['where']['op_module'] = ['eq', DcHtml($this->query['op_module'])];
            }
            if($this->query['searchText']){
                $args['where']['op_value'] = ['like','%'.DcHtml($this->query['searchText']).'%'];
            }
            $list = \daicuo\Nav::all($args);
            if(is_null($list)){
                return json([]);
            }
            return json($list);
        }
		$this->assign('query', $this->query);
		return $this->fetch();
	}
	
	//排序（拖拽ajax）
	public function sort(){
		$ids = explode(',',input('get.id'));
		if(!$ids[0]){
			$this->error(lang('mustIn'));
		}
        //批量更新
		$list = array();
		foreach($ids as $key=>$value){
			$list[$key]['op_id'] = $value;
			$list[$key]['op_order'] = $key;
		}
        //带主键批量函数处理排序
		if( !dbWriteAuto('common/Op', $list) ){
			$this->error(config('daicuo.error'));
		}
        //清理缓存
		DcCacheTag('common/Op/Item', 'tag', 'clear');
		$this->success(lang('success'));
	}
    
    //修改（表单）
	public function edit(){
		$id = input('id/d',0);
		if(!$id){
			$this->error(lang('mustIn'));
		}
		//查询数据
        $data = \daicuo\Nav::get_id($id, false);
        if( is_null($data) ){
            $this->error(lang('empty'));
        }
		$this->assign('data', $data);
		return $this->fetch();
	}
    
    //修改（数据库）
	public function update(){
		$data = input('post.');
        if(!$data['op_id']){
			$this->error(lang('mustIn'));
		}
        $info = \daicuo\Nav::update_id($data['op_id'], $data);
        if(is_null($info)){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}		
    
    //删除(数据库)
	public function delete(){
		$ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        foreach($ids as $id){
            \daicuo\Nav::delete_id($id);
        }
        $this->success(lang('success'));
	}
	
	
	//添加数据保存至数据库
	public function save(){
        $op_id = \daicuo\Nav::save(input('post.'));
		if($op_id < 1){
			$this->error(config('daicuo.error'));
		}
		$this->success(lang('success'));
	}
	
}