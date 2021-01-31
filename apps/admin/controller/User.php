<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class User extends Admin
{
    //添加(数据库)
	public function save()
    {
        // 表单验证名称
        config('common.validate_name', 'common/User');
        // 表单验证场景
        config('common.validate_scene', 'save');
        // 添加至数据库
        $user_id = \daicuo\User::save(input('post.'));
		if($user_id < 1){
			$this->error(\daicuo\User::getError());
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
        foreach($ids as $user_id){
            \daicuo\User::delete_user_by('user_id', $user_id);
        }
        $this->success(lang('success'));
	}
    
	//修改（表单）
	public function edit()
    {
		if( !$user_id = input('id/d',0) ){
			$this->error(lang('mustIn'));
		}
		//查询数据
        $data = \daicuo\User::get_user_by('user_id', $user_id, false);
        if( is_null($data) ){
            $this->error(\daicuo\User::getError());
        }
        //删除密码
        unset($data['user_pass']);
		$this->assign('data', $data);
		return $this->fetch();
	}
	
	//修改（数据库）
	public function update()
    {  
		$data = input('post.');
        if($data['user_id']){
            // 表单验证名称
            config('common.validate_name', 'common/User');
            // 表单验证场景
            config('common.validate_scene', 'update');
            // 修改数据库
            $info = \daicuo\User::update_user_by('user_id', $data['user_id'], $data);
            // 返回结果
            if(is_null($info)){
                $this->error(\daicuo\User::getError());
            }
        }
        $this->success(lang('success'));
	}
    
    // 数据查询
	public function index()
    {
        if($this->request->isAjax()){
            $args = array();
            $args['cache'] = false;
            $args['field'] = '*';
            $args['sort'] = input('get.sortName/s','user_id');
            $args['order'] = input('get.sortOrder/s','desc');
            //$args['fetchSql'] = true;
            //$args['where'] = [];
            //$args['whereOr'] = ['user_name'=>['like','%tan%'],'user_email'=>['like','%adm%']];
            if($search = input('searchText/s')){
                $args['whereOr'] = [
                    'user_name'=>['like','%'.$search.'%'],
                    'user_email'=>['like','%'.$search.'%'],
                    'user_mobile'=>['like','%'.$search.'%'],
                ];
            }
            //$args['whereHas'] = [];
            //$args['with'] = ['userMeta'];
            //$args['limit'] = 0;
            //$args['page'] = 0;
            $args['paginate'] = [
                'list_rows' => input('pageSize/d',10),
                'page' => input('pageNumber/d',1),
                'var_page' => 'pageNumber',
            ];
            $list = \daicuo\User::all($args);
            if(!is_null($list)){
                return json($list->toArray());
            }else{
                return json(['total'=>0,'data'=>'']);
            }
		}
        $this->assign('query', $this->query);
		return $this->fetch();
	}
}