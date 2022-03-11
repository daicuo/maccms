<?php
namespace app\common\validate;

use think\Validate;

class Op extends Validate
{

	protected $rule = [
		'op_name'  =>  'require',
	];
	
	protected $message = [
		'op_name.require' => '{%op_name_require}',
	];

}