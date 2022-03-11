<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Version extends Admin
{
	public function index()
    {
		$version = input('version/s');//本地版本号
        $module  = input('module/s','daicuo');//应用名称
        if(!$version){
			return json(array('code'=>0));
		}
        $json = \daicuo\Service::apiUpgrade(['event'=>'check','module'=>$module,'version'=>$version]);
		if($json['version']){
            if( version_compare($version, $json['version'], '<') ){
                if($module == 'daicuo'){
                    $upgrade = DcUrl('update/online');
                }else{
                    $upgrade = DcUrl('apply/upgrade',['module'=>$module]);
                }
                return json([
                    'code'    => 1,
                    'version' => $json['version'],
                    'msg'     => lang('update_down'),
                    'update'  => $json['update'],
                    'upgrade' => $upgrade,
                ]);
            }
		}
		return json(array('code'=>0));//版本一致
	}
}