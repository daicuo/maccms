<?php
namespace app\api\controller;

use app\common\controller\Api;

class Index extends Api
{
	public function _initialize()
    {
		parent::_initialize();
	}

	public function index()
    {
        $api = [];
        $api['login']   = url('api/token/login', ['user_captcha'=>'test','user_name'=>'test','user_pass'=>'test'], '', true);
        $api['refresh'] = url('api/token/refresh', '', '', true);
        $api['delete']  = url('api/token/delete', '', '', true);
        $this->success( lang('success'), $api );
	}
}