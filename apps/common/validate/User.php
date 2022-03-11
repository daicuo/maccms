<?php
namespace app\common\validate;

use think\Validate;

class User extends Validate
{
	
	protected $rule = [
		'user_name'         =>  'require|unique:user',
		'user_pass'         =>  'require',
		'user_pass_confirm' =>  'require|confirm:user_pass',
		'user_email'        =>  'requireWith|email|unique:user',
		'user_mobile'       =>  'requireWith|mobile|unique:user',
	];
	
	protected $message = [
		'user_name.require'         => '{%user_name_require}',
		'user_name.unique'          => '{%user_name_unique}',
		'user_pass.require'         => '{%user_pass_require}',
		'user_pass_confirm.require' => '{%user_pass_confirm_require}',
		'user_pass_confirm.confirm' => '{%user_pass_confirm_confirm}',
        'user_email.require'        => '{%user_email_require}',
        'user_email.unique'         => '{%user_email_unique}',
		'user_email.email'          => '{%user_email_isemail}',
        'user_mobile.require'       => '{%user_mobile_require}',
        'user_mobile.unique'        => '{%user_mobile_unique}',
		'user_mobile.mobile'        => '{%user_mobile_ismobile}',
	];
	
	protected $scene = [
        'empty'       =>  '',
        'auto'        =>  ['user_name','user_pass'],
		'save'        =>  ['user_name','user_pass','user_email','user_mobile'],
        'register'    =>  ['user_name','user_pass','user_pass_confirm','user_email','user_mobile'],
        'registerapi' =>  ['user_name','user_pass','uuser_pass_confirm'],
        'update'      =>  ['user_email','user_mobile'],
        'email'       =>  ['user_email'],
        'mobile'      =>  ['user_mobile'],
	];
    
    protected function mobile($value, $rule, $data, $field)
    {
		if(!is_mobile($value)){
            return lang('user_mobile_ismobile');
		}
		return true;
	}
}