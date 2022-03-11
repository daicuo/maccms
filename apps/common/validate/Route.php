<?php
namespace app\common\validate;

use think\Validate;

class Route extends Validate
{
	
	protected $rule = [
		'rule'      => 'require|checkRule',
		'address'   => 'require|checkAddress',
        'option'    => 'checkJson',
        'pattern'   => 'checkJson',
	];
	
	protected $message = [
		'rule.require' => '{%rule_require}',
		'address.require' => '{%address_require}',
	];
	
	protected function checkRule($value, $rule, $data)
    {
		if(is_numeric($value)){
			return lang('rule_number');
		}
		return true;
	}
	
	protected function checkAddress($value, $rule, $data)
    {
		if(count(explode('/',$value)) < 3){
			return lang('address_demo');
		}
		return true;
	}
    
    protected function checkJson($value, $rule, $data, $field)
    {
		if($value){
            if(!json_decode($value, true)){
                return lang($field.'_json');
            }
		}
		return true;
	}

}