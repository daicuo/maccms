<?php
namespace app\common\validate;

use think\Validate;

class TermMuch extends Validate
{
	
	protected $rule = [
		'term_much_type'   => 'require|in:category,tag',
	];
	
	protected $message = [
		'term_much_type.require' => '{%term_much_type_require}',
        'term_much_type.in'      => '{%term_much_type_in}',
	];
    
    //验证场景
	protected $scene = [
		'save'  =>  ['term_much_type'],
	];

}