<?php
namespace app\common\validate;

use think\Validate;

class TermMeta extends Validate
{
	
	protected $rule = [
		'term_meta_value' =>  'require',
	];
	
	protected $message = [
		'term_meta_value.require' => '{%term_meta_value}',
	];

}