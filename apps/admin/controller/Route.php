<?php
namespace app\admin\controller;

use app\common\controller\Admin;

/**
 * 路由管理
 */
class Route extends Admin{
	
	//路由管理
	public function index()
    {
        if($this->request->isAjax()){
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_status';
            $args['sort']  = input('get.sortName/s','op_id');
            $args['order'] = input('get.sortOrder/s','desc');
            //$args['fetchSql'] = true;
            if( $this->query['op_module'] ){
                $args['where']['op_module'] = ['eq', DcHtml($this->query['op_module'])];
            }
            if( $this->query['searchText'] ){
                $args['where']['op_value'] = ['like','%'.DcHtml($this->query['searchText'].'%')];
            }
            $infos = \daicuo\Route::all($args);
            foreach($infos as $key=>$value){
                $infos[$key]['operate'] = base64_encode($value["rule"]);
            }
            if($infos){
                return json($infos);
            }
            return json([]);
		}
		$this->assign('query', $this->query);
		return $this->fetch();
	}
	
	//修改
	public function edit(){
        $id = input('id/d',0);
		if(!$id){
			$this->error(lang('mustIn'));
		}
		//查询数据
        $data = \daicuo\Route::get_id($id, false);
        if( is_null($data) ){
            $this->error(lang('empty'));
        }
		$this->assign('data', $data);
		return $this->fetch();
	}
	
	//修改一条规则到数据库
	public function update(){
        $data = input('post.');
        if(!$data['op_id']){
			$this->error(lang('mustIn'));
		}
        $info = \daicuo\Route::update_id($data['op_id'], $data);
        if(is_null($info)){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
		
	//删除路由规则
	public function delete(){
        $ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        foreach($ids as $id){
            \daicuo\Route::delete_id($id);
        }
        $this->success(lang('success'));
	}
	
	//新加一条规则到数据库
	public function save(){
        $op_id = \daicuo\Route::save(input('post.'));
		if($op_id < 1){
			$this->error(config('daicuo.error'));
		}
		$this->success(lang('success'));
	}
    
    //首页快捷设置
	public function home()
    {
        //定义首页路由
		$data = array();
        $data['rule'] = '/';
        $data['address'] = input('address/s','index/index/index');
        //查询是否已经设置其它模块为首页
        $args = array();
        $args['cache'] = false;
        $infos = \daicuo\Route::all($args);
        foreach($infos as $key=>$value){
            if($value['rule'] == '/'){
                $data['id'] = $value['op_id'];
                break;
            }
        }
        //写入数据库
        if($data['id']){
            $status = \daicuo\Route::update_id($data['id'],$data);
        }else{
            $status = \daicuo\Route::save($data);
        }
        if( !$status ){
			$this->error(lang('fail'));
		}
		$this->success(lang('success'));
	}
	
}