<?php
namespace app\common\validate;

use think\Validate;

class Nav extends Validate{
	
	protected $rule = [
		'nav_text'  =>  'require',
	];
	
	protected $message = [
		'nav_text.require' => '{%nav_text_require}',
	];
	
	protected $scene = [
        
	];

}