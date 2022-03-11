<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Tag extends Admin
{
    //定义表单字段列表
    protected function fields($data=[])
    {
        return model('admin/Tag','loglic')->fields($data);
    }
    
    //定义表单初始数据
    protected function formData()
    {
        if( $id = input('id/d',0) ){
            return model('common/Term','loglic')->getId($id, false);
		}
        return [];
    }

    //定义表格数据（JSON）
    protected function ajaxJson()
    {
        //查询参数
        $args = array();
        $args['cache']      = false;
        $args['controll']   = 'tag';
        $args['limit']      = DcEmpty($this->query['pageSize'],50);
        $args['page']       = DcEmpty($this->query['pageNumber'],1);
        $args['sort']       = DcEmpty($this->query['sortName'],'term_id');
        $args['order']      = DcEmpty($this->query['sortOrder'],'desc');
        //基础字段条件
        $args['search']     = $this->query['searchText'];
        $args['status']     = $this->query['term_status'];
        $args['module']     = $this->query['term_module'];
        $args['action']     = $this->query['term_action'];
        $args['parent']     = DcEmpty($this->query['term_parent'],'');
        //扩展字段条件
        $args['meta_query'] = DcMetaQuery(model('common/Term','loglic')->metaList($args['module'],$args['controll']), $this->query);
        //自定义字段排序
        if( !in_array($args['sort'],model('common/Attr','loglic')->termSort()) ){
            $args['meta_key'] = $args['sort'];
            $args['sort']     = 'meta_value_num';
        }
        //数据查询
        $list = model('common/Term','loglic')->select(DcArrayEmpty($args));
        //数据返回
        return DcEmpty($list,['total'=>0,'data'=>[]]);
    }
    
	//添加(数据库)
	public function save()
    {
        $id = model('common/Tag','loglic')->write(input('post.'));
		if($id < 1){
			$this->error(\daicuo\Term::getError());
		}
        
		$this->success(lang('success'));
	}
    
    //删除(数据库)
	public function delete()
    {
		$ids = input('id/a');
		if(!$ids){
			$this->error(lang('errorIds'));
		}
        
        model('common/Term','loglic')->deleteIds($ids);

        $this->success(lang('success'));
	}
    
    //修改（数据库）
	public function update()
    {
		$data = input('post.');
        if(!$data['term_id']){
            $this->error(lang('errorIds'));
        }
        
        $info = model('common/Tag','loglic')->write($data);
        if(is_null($info)){
            $this->error(\daicuo\Term::getError());
        }
        $this->success(lang('success'));
	}
    
    //快速修改状态
    public function status()
    {
        if( !$ids = input('post.id/a') ){
            $this->error(lang('errorIds'));
        }
        
        $result = model('common/Term','loglic')->status($ids,input('request.value/s', 'hidden'));
        
        $this->success(lang('success'));
    }
    
    //预览
    public function preview()
    {
        if(!$info= model('common/Term','loglic')->getId(input('id/d',0), false)){
            $this->error(lang('empty'));
        }
        //去掉后台入口文件
        $url = str_replace($this->request->baseFile(), '', model('common/Term','loglic')->url($info));
        //跳转至前台
        $this->redirect($url,302);
    }
}