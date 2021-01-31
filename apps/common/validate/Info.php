<?php
namespace app\common\validate;

use think\Validate;

class Info extends Validate
{
	
	protected $rule = [
        'info_name'        => 'require',
        'info_id'          => 'require',
	];
	
	protected $message = [
		'info_name.require'       => '{%info_name_require}',
        'info_id.require'         => '{%info_id_require}',
	];
	
	//验证场景
	protected $scene = [
		'save'        =>  ['info_name'],
		'update'      =>  ['info_name','info_id'],
	];

}