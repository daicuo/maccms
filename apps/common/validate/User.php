<?php
namespace app\common\validate;

use think\Validate;

class User extends Validate{
	
	protected $rule = [
		'user_name'       =>  'require|unique:user',
		'user_pass'       =>  'require',
		'user_pass_re'    =>  'require|confirm:user_pass',
		'user_email'      =>  'require|email|unique:user',
		'user_mobile'     =>  'require|mobile|unique:user',
        'user_id'         =>  'require',
	];
	
	protected $message = [
		'user_name.require'       => '{%user_name_require}',
		'user_name.unique'        => '{%user_name_unique}',
		'user_pass.require'       => '{%user_pass_require}',
		'user_pass_re.require'    => '{%user_pass_re_require}',
		'user_pass_re.confirm'    => '{%user_pass_re_confirm}',
        'user_email.require'      => '{%user_email_require}',
        'user_email.unique'       => '{%user_email_unique}',
		'user_email.email'        => '{%user_email_isemail}',
        'user_mobile.require'     => '{%user_mobile_require}',
        'user_mobile.unique'      => '{%user_mobile_unique}',
		'user_mobile.mobile'      => '{%user_mobile_ismobile}',
	];
	
	protected $scene = [
		'admin_save'  =>  ['user_name','user_pass','user_email','user_mobile'],
		'admin_update'  =>  ['user_email','user_mobile','user_id'],
	];
    
    protected function mobile($value, $rule, $data, $field)
    {
		if(!is_mobile($value)){
            return lang('user_mobile_ismobile');
		}
		return true;
	}

}