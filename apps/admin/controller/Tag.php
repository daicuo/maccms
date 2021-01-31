<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Tag extends Admin
{
	//添加(数据库)
	public function save()
    {
        config('common.validate_name', 'common/Term');
        config('common.validate_scene', 'save');
        $term_id = \daicuo\Term::save(input('post.'));
		if($term_id < 1){
			$this->error(\daicuo\Term::getError());
		}
		$this->success(lang('success'));
	}
    
    //删除(数据库)
	public function delete()
    {
		$ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        foreach($ids as $id){
            \daicuo\Term::delete_id($id);
        }
        $this->success(lang('success'));
	}
    
    //修改（表单）
	public function edit()
    {
		$term_id = input('id/d',0);
		if(!$term_id){
			$this->error(lang('mustIn'));
		}
		//查询数据
        //config('cache.expire_detail', -1);
        $data = \daicuo\Term::get_id($term_id, false);
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
        if($data['term_id']){
            config('common.validate_name', 'common/Term');
            config('common.validate_scene', 'update');
            $info = \daicuo\Term::update_id($data['term_id'], $data);
            if(is_null($info)){
                $this->error(\daicuo\Term::getError());
            }
        }
        $this->success(lang('success'));
	}
    
	public function index()
    {
        if($this->request->isAjax()){
            $args = array();
            $args['cache'] = false;
            $args['sort'] = input('get.sortName/s','term_id');
            $args['sort'] = str_replace('term_id', 'term.term_id', $args['sort']);
            $args['order'] = input('get.sortOrder/s','desc');
            $args['where']['term_much_type'] = ['eq', 'tag'];
            //$args['with'] = ['termMeta'];
            //$args['fetchSql'] = true;
            $args['paginate'] = [
                'list_rows' => input('pageSize/d',10),
                'page' => input('pageNumber/d',1),
            ];
            if($this->query['op_module']){
                $args['where']['term_module'] = ['eq', DcHtml($this->query['op_module'])];
            }
            if($this->query['searchText']){
                $args['where']['term_name|term_slug'] = ['like','%'.DcHtml($this->query['searchText']).'%'];
            }
            $list = \daicuo\Term::all($args);
            if( is_null($list) ){
                return json(['total'=>0,'data'=>'']);
            }
            return json($list->toArray());
		}
        $this->assign('query', $this->query);
		return $this->fetch();
	}
}