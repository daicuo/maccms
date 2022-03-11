<?php
namespace app\maccms\event;

use app\common\controller\Addon;

class Adsense extends Addon
{
	
	public function _initialize()
    {
		parent::_initialize();
	}
    
    //电脑广告
	public function index()
    {
        $items = [
            'header_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.header_pc'),
            ],
            'thread_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.thread_pc'),
            ],
            'footer_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.footer_pc'),
            ],
            'left_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.left_pc'),
            ],
            'center_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.center_pc'),
            ],
            'right_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.right_pc'),
            ],
            'one_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.one_pc'),
            ],
            'two_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.two_pc'),
            ],
            'three_pc' => [
                'type'  => 'textarea',
                'value' => config('maccms.three_pc'),
            ],
        ];
        
        $this->assign('items', DcFormItems($this->items($items)));
        
        return $this->fetch('maccms@adsense/index');
	}
    
    //手机广告
    public function wap()
    {
        $items = [
            'header_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.header_wap'),
            ],
            'thread_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.thread_wap'),
            ],
            'footer_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.footer_wap'),
            ],
            'left_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.left_wap'),
            ],
            'center_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.center_wap'),
            ],
            'right_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.right_wap'),
            ],
            'one_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.one_wap'),
            ],
            'two_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.two_wap'),
            ],
            'three_wap' => [
                'type'  => 'textarea',
                'value' => config('maccms.three_wap'),
            ],
        ];
        
        $this->assign('items', DcFormItems($this->items($items)));
        
        return $this->fetch('maccms@adsense/wap');
    }
    
    //批量属性
    private function items($items=[])
    {
        foreach($items as $key=>$value){
            $items[$key]['title'] = lang('mac_'.$key);
            $items[$key]['rows']  = 4;
            $items[$key]['tips']  = '<p>调用代码</p><p>'.DcTplLabelOp('maccms',$key).'</p>';
            if(!isset($value['placeholder'])){
                $items[$key]['placeholder'] = lang('mac_'.$key.'_placeholder');
            }
        }
        return $items;
    }
}