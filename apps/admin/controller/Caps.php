<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Caps extends Admin
{
    //批量新增与修改配置
	public function update()
    {
        //表单处理
        $post   = input('post.');
        $action = DcEmpty($post['op_action'],'back');
        $module = $post['op_module'];//可有可无
        unset($post['op_module']);
        unset($post['op_action']);
        
        //拼装新数据
        $posts = [];
        foreach($post as $caps=>$value){
            foreach($value as $key=>$role){
                $posts[] = [
                    'op_name'     => $role,
                    'op_value'    => $caps,
                    'op_module'   => DcEmpty($module,'admin'),
                    'op_controll' => 'auth',
                    'op_action'   => $action,
                    'op_status'   => 'normal',
                    'op_order'    => 0,
                ];
            }
        }
        //dump($posts);
        
        //先删除旧权限再新增
        if($posts){
            db('op')->where(DcArrayEmpty(['op_module'=>$module,'op_controll'=>'auth','op_action'=>['eq',$action]]))->delete();
            
            \daicuo\Op::save_all($posts);
            
            DcCache('auth_all', null);
        }
        $this->success(lang('success'));
	}
    
    //权限设置
    public function index()
    {
        $module = $this->query['op_module'];//可有可无
        
        $this->assign('items', $this->formItems($module,'back'));
        
		return $this->fetch();
    }
    
    //前台权限
    public function front()
    {
        $module = $this->query['op_module'];//可有可无
        
        $this->assign('items', $this->formItems($module,'front'));
        
		return $this->fetch();
    }
    
    //生成表单字段列表
    private function formItems($module='admin',$action='back'){
        //用户组列表
        $roles = model('common/Role','loglic')->option();
        unset($roles['administrator']);
        if($action == 'back'){
            unset($roles['guest']);
        }
        
        //所有权限节点对应的用户组keyValue形式
        $authItem = model('common/Auth','loglic')->select(DcArrayEmpty([
            'field'  => 'op_id,op_name,op_value,op_action',
            'action' => ['eq',$action],
            'module' => $module,
        ]));
        $authRole = [];
        foreach($authItem as $key=>$value){
            //$field = DcParseUrl($value['op_value'],'path');
            $field = $value['op_value'];
            if(is_array($authRole[$field])){
                array_push($authRole[$field],$value['op_name']);
            }else{
                $authRole[$field] = [$value['op_name']];
            }
        }

        //拼装表单字段
        $items = [
            'op_module' => [
                'type'  => 'hidden',
                'value' => $module,
            ],
            'op_action' => [
                'type'  => 'hidden',
                'value' => $action,
            ],
        ];
        //需要插件应用定义caps节点节表
        foreach($this->getCaps($module,$action) as $key=>$field){
            $items[$field] = [
                'type'   => 'checkbox',
                'option' => $roles,
                'value'  => $authRole[$field],
                'class_right_check' => 'form-check form-check-inline py-1',
            ];
        }
        //返回数据
        return DcFormItems($items);
    }
    
    //需要插件应用定义caps节点节表
    private function getCaps($module='admin',$action='back'){
        $module = DcEmpty($module,'admin');
        if($action == 'front'){
            return model($module.'/Caps','loglic')->front();
        }else{
            return model($module.'/Caps','loglic')->back();
        }
    }
}