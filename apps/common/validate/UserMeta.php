<?php
namespace app\common\validate;

use think\Validate;

class UserMeta extends Validate
{
	
	protected $rule = [
		'user_meta_value' =>  'require',
	];
	
	protected $message = [
		'user_meta_value.require' => '{%user_meta_value}',
	];
	
	protected $scene = [
        
	];

}