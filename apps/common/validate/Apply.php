<?php
namespace app\common\validate;

use think\Validate;

class Apply extends Validate
{
	
    protected $rule = [
        'module'  =>  'alpha',
    ];

    protected $message = [
        'module.alpha' => '{%alpha_module}',
    ];

    protected $scene = [

    ];

}