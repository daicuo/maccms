<?php
namespace app\admin\controller;

use app\common\controller\Admin;

/**
 * 钩子管理
 */
class Hook extends Admin
{
    //管理
    public function index()
    {
        if($this->request->isAjax()){
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_status';
            $args['sort']  = input('get.sortName/s','op_id');
            $args['order'] = input('get.sortOrder/s','desc');
            if( $this->query['op_module'] ){
                $args['where']['op_module'] = ['eq',DcHtml($this->query['op_module'])];
            }
            if( $this->query['searchText'] ){
                $args['where']['op_value'] = ['like','%'.DcHtml($this->query['searchText'].'%')];
            }
            $infos = \daicuo\Hook::all($args);
            if($infos){
                return json($infos);
            }
            return json([]);
		}
        $this->assign('query', $this->query);
        return $this->fetch();
    }
	
	//排序（拖拽ajax）
    public function sort()
    {
        $ids = explode(',',input('get.id'));
        $list = array();
        foreach($ids as $key=>$value){
            $list[$key]['op_id'] = $value;
            $list[$key]['op_order'] = $key;
        }
        if( !dbWriteAuto('op', $list) ){
            $this->error(config('daicuo.error'));
        }
        DcCacheTag('common/Hook/Item', 'tag', 'clear');
        $this->success(lang('success'));
	}
	
	//修改（表单）
	public function edit()
    {
		$id = input('id/d',0);
		if(!$id){
			$this->error(lang('mustIn'));
		}
		//查询数据
        $data = \daicuo\Hook::get_id($id, false);
        if( is_null($data) ){
            $this->error(lang('empty'));
        }
		$this->assign('data', $data);
		return $this->fetch();
	}
	
	//修改（数据库）
	public function update()
    {
		$data = input('post.');
        if(!$data['op_id']){
			$this->error(lang('mustIn'));
		}
        $info = \daicuo\Hook::update_id($data['op_id'], $data);
        if(is_null($info)){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}		
	
	//删除（数据库）
	public function delete()
    {
	    $ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        foreach($ids as $id){
            \daicuo\Hook::delete_id($id);
        }
        $this->success(lang('success'));
	}
	
	//添加数据保存至数据库
    public function save()
    {
        $op_id = \daicuo\Hook::save(input('post.'));
		if($op_id < 1){
			$this->error(config('daicuo.error'));
		}
		$this->success(lang('success'));
    }	
}