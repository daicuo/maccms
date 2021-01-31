<?php
namespace app\common\validate;

use think\Validate;

/*
**用于未格式化前的表单数据验证
*/
class Term extends Validate
{
	
	protected $rule = [
		'term_name'        => 'require|length:1,60|unique_type',
        'term_id'          => 'require',
        'term_much_type'   => 'require|in:category,tag',
	];
	
	protected $message = [
		'term_name.require'      => '{%term_name_require}',
		'term_name.length'       => '{%term_name_length}',
        'term_much_type.require' => '{%term_much_type_require}',
        'term_much_type.in'      => '{%term_much_type_in}',
	];
	
	//验证场景
	protected $scene = [
		'save'        =>  ['term_name','term_much_type'],
		'update'      =>  ['term_name','term_id'],
	];
    
    //队列名唯一验证
    protected function unique_type($value, $rule, $data, $field)
    {
        if($data['term_id']){
            $info = DcDbGet('common/Term', ['term_id'=>['neq',$data['term_id']],'term_name'=>['eq',$value]], ['term_much']);
        }else{
            $info = DcDbGet('common/Term', ['term_name'=>['eq',$value]], ['term_much']);
            //$info->termMuch()->where('term_much_type','category')->find();
        }
        //无记录直接验证通过
        if(is_null($info->term_much_type)){
            return true;
        }
        //有记录不相同时通过
        if($info->term_much_type != $data['term_much_type']){
            return true;
        }
		return lang('tag_name_unique');
	}

}