<?php
namespace app\maccms\event;

use app\common\controller\Addon;

class Slide extends Addon
{
	
	public function _initialize()
    {
		parent::_initialize();
	}
    
	public function index()
    {
        $items = [
            'slide_index' => [
                'type'  => 'json',
                'value' => config('maccms.slide_index'),
                'rows'  => 16,
            ],
            'slide_index_m' => [
                'type'  => 'json',
                'value' => config('maccms.slide_index_m'),
                'rows'  => 16,
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
        return $this->fetch('maccms@slide/index');
	}
}