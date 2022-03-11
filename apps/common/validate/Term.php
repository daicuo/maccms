<?php
namespace app\common\validate;

use think\Validate;

class Term extends Validate
{
	
	protected $rule = [
		'term_name'        => 'require|length:1,60|unique_name',
        'term_id'          => 'require',
        'term_type'        => 'require|in:category,tag',
	];
	
	protected $message = [
		'term_name.require'   => '{%term_name_require}',
		'term_name.length'    => '{%term_name_length}',
        'term_type.require'   => '{%term_type_require}',
        'term_type.in'        => '{%term__type_in}',
	];
	
	//验证场景
	protected $scene = [
		'save'    => ['term_name'],
		'update'  => ['term_name'],
	];
    
    //队列名唯一验证
    protected function unique_name($value, $rule, $data, $field)
    {
        $where = [];
        $where['term_module']   = DcEmpty($data['term_module'],'common');
        $where['term_controll'] = DcEmpty($data['term_controll'],'category');
        $where['term_parent']   = intval($data['term_parent']);;
        $where['term_name']     = $value;
        if($data['term_id']){
            $where['term_id']   = ['neq',$data['term_id']];
        }
        $info = DcDbGet('common/Term', $where, false);
        //无记录直接验证通过
        if(is_null($info)){
            return true;
        }
        return lang('tag_name_unique');
	}

}