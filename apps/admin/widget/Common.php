<?php
namespace app\admin\widget;

use think\Controller;

class Common extends Controller
{
	
	public function welcome()
    {
        return $this->fetch(APP_PATH.'admin'.DS.'view'.DS.'hook'.DS.'welcome.tpl'); 

    }

}