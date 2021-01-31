<?php
namespace app\common\validate;

use think\Validate;

class Hook extends Validate
{

	protected $rule = [
		'hook_name'     =>  'require|checkNumber',
        'hook_path'     =>  'require|checkPath',
        'hook_overlay'  =>  'checkOverlay',
	];
	
	protected $message = [
		'hook_name.require' => '{%hook_name_require}',
        'hook_path.require' => '{%hook_path_require}',
	];
	
	protected $scene = [
        
	];
    
    protected function checkNumber($value, $rule, $data)
    {
		if( is_numeric($value) ){
            return lang('hook_name_number');
        }
		return true;
	}
    
    protected function checkPath($value, $rule, $data)
    {
        $value = trim($value);
		if(count(explode('\\',$value)) < 2){
			return lang('hook_path_fail');
		}
        if( false == class_exists($value) ){
            return lang('hook_path_none');
        }
        if( !method_exists( new $value(), camelize($data['hook_name']) ) ){
            return lang('hook_path_method');
        }
		return true;
	}
    
    protected function checkOverlay($value, $rule, $data)
    {
        if( in_array($data['hook_name'], ['app_int','app_begin','module_init']) && ($data['hook_overlay'] == 'yes') ){
            return lang('hook_overlay_name');
        }
		return true;
	}

}