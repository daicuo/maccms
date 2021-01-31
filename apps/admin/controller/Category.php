<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Category extends Admin
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
            if($this->query['sortName'] == 'tree' && $this->query['searchText']=='' && $this->query['op_module']==''){
                return $this->tree();
            }
            $args = array();
            $args['cache'] = false;
            $args['sort'] = DcHtml( DcEmpty($this->query['sortName'], 'term_id') );
            $args['sort'] = str_replace('tree', 'term.term_id', $args['sort']);
            $args['order'] = DcHtml( DcEmpty($this->query['sortOrder'], 'desc') );
            $args['where']['term_much_type'] = ['eq', 'category'];
            //$args['with'] = ['termMeta'];
            //$args['fetchSql'] = true;
            $args['paginate'] = [
                'list_rows' =>DcEmpty((int) $this->query['pageSize'], 20),
                'page' =>DcEmpty((int) $this->query['pageNumber'], 1),
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
    
    //默认层级结构显示
    private function tree(){
        $args = array();
        $args['cache'] = false;
        $args['sort'] = 'term.term_id';
        $args['order'] = 'desc';
        $args['type'] = 'category';
        //$args['ids'] = '1,2,3';
        //$args['module'] = 'index';
        //$args['name'] = '动作';
        //$args['slug'] = 'dongzuo';
        //$args['searchText'] = 'searchwd';
        $args['paginate'] = [
            'list_rows' =>DcEmpty((int) $this->query['pageSize'], 20),
            'page' =>DcEmpty((int) $this->query['pageNumber'], 1),
        ];
        $list = \daicuo\Term::tree($args);
        return json($list);
    }

}