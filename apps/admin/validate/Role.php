<?php
namespace app\admin\validate;

use think\Validate;

class Role extends Validate
{

	protected $rule = [
		'op_name'  =>  'require|unique_name',
	];
	
	protected $message = [
		'op_name.require' => '{%op_name_require}',
	];
    
    //唯一验证
    protected function unique_name($value, $rule, $data, $field)
    {
        $where = [];
        $where['op_controll'] = 'role';
        $where['op_name']     = $value;
        if($data['op_id']){
            $where['op_id']   = ['neq',$data['op_id']];
        }
        $id = db('op')->where($where)->value('op_id');
        //无记录直接验证通过
        if(is_null($id)){
            return true;
        }
        return lang('role_unique');
	}

}