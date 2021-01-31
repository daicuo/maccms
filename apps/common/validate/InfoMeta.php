<?php
namespace app\common\validate;

use think\Validate;

class InfoMeta extends Validate
{
	
	protected $rule = [
		'info_meta_value' =>  'require',
	];
	
	protected $message = [
		'info_meta_value.require' => '{%info_meta_value}',
	];

}