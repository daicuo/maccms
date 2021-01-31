<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Version extends Admin
{
	
	//版本比较是否一致
	public function index()
    {
		$version = input('version/s');//本地版本号
        $module = input('module/s','daicuo');//应用名称
        if(!$version){
			return json(array('code'=>0));
		}
        $service = new \daicuo\Service();
        $json = $service->apiUpgrade(['event'=>'check','module'=>$module,'version'=>$version]);
		if($json['version']){
			if( !\daicuo\Version::compare($version, $json['version']) ){//需要升级
				return json(array('code'=>1,'update'=>DcHtml($json['update']),'msg'=>lang('update_to').DcHtml($json['version'])));
			}
		}
		return json(array('code'=>0));//版本一致
	}

}
