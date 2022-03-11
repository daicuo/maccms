<?php
namespace app\maccms\event;

use app\common\controller\Addon;

class Weixin extends Addon
{
	
	public function _initialize()
    {
		parent::_initialize();
	}
    
    //管理首页
	public function index()
    {
        $items = [
            'wx_token' => [
                'type'  => 'text',
                'value' => config('maccms.wx_token'),
            ],
            'wx_limit' => [
                'type'  => 'number',
                'value' => DcEmpty(config('maccms.wx_limit'),5),
            ],
            'wx_follow' => [
                'type'  => 'text',
                'value' => config('maccms.wx_follow'),
            ],
            'wx_none' => [
                'type'  => 'text',
                'value' => config('maccms.wx_none'),
            ],
            'wx_domain' => [
                'type'  => 'text',
                'value' => config('maccms.wx_domain'),
            ],
            'wx_keywords' => [
                'type'  => 'json',
                'value' => DcEmpty(config('maccms.wx_keywords'),json_encode([['title'=>'测试','content'=>'我是测试','picurl'=>'https://cdn.daicuo.cc/images/daicuo/logo.png','url'=>'http://www.daicuo.net']])),
            ],
        ];
        foreach($items as $key=>$value){
            $items[$key]['title']          = lang('mac_'.$key);
            if(!isset($value['placeholder'])){
                $items[$key]['placeholder'] = lang('mac_'.$key.'_placeholder');
            }
        }
        //
        $this->assign('items', DcFormItems($items));
        return $this->fetch('maccms@weixin/index');
	}
}