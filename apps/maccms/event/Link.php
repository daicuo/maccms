<?php
namespace app\maccms\event;

use app\common\controller\Addon;

class Link extends Addon
{
    
	public function _initialize()
    {
		parent::_initialize();
	}
    
    //管理首页
	public function index()
    {
        $items = [
            'link_index' => [
                'type'  => 'json',
                'value' => config('maccms.link_index'),
                'rows'  => 18,
            ],
            'link_footer' => [
                'type'  => 'json',
                'value' => config('maccms.link_footer'),
                'rows'  => 18,
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
        return $this->fetch('maccms@link/index');
	}
}