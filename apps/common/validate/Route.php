<?php
namespace app\common\validate;
use think\Validate;

class Route extends Validate
{
	
	protected $rule = [
		'rule'  =>  'require|checkRule',
		'address'   => 'require|checkAddress',
        'option'   => 'checkJson',
        'pattern'   => 'checkJson',
	];
	
	protected $message = [
		'rule.require' => '{%route_rule_require}',
		'address.require' => '{%route_address_require}',
	];
	
	protected $scene = [
    
	];
	
	protected function checkRule($value, $rule, $data)
    {
		if(is_numeric($value)){
			return lang('route_rule_number');
		}
		return true;
	}
	
	protected function checkAddress($value, $rule, $data)
    {
		if(count(explode('/',$value)) < 3){
			return lang('route_address_demo');
		}
		return true;
	}
    
    protected function checkJson($value, $rule, $data, $field)
    {
		if($value){
            if(!json_decode($value, true)){
                return lang('route_'.$field.'_json');
            }
		}
		return true;
	}

}