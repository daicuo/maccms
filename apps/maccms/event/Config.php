<?php
namespace app\maccms\event;

use app\common\controller\Addon;

class Config extends Addon
{
	
	public function _initialize()
    {
		parent::_initialize();
	}
    
    //管理首页
	public function index()
    {
        $items = [
            'api_url' => [
                'type'       => 'text',
                'value'      => config('maccms.api_url'),
                'tips'       => lang('mac_api_bind'),
                'class_tips' => 'api-add pt-1 col-12 col-md-3',
            ],
            'api_type' => [
                'type'       => 'text',
                'value'      => config('maccms.api_type'),
                'tips'       => 'json|xml|feifeicms',
            ],
            'api_params' => [
                'type'  => 'text',
                'value' => config('maccms.api_params'),
            ],
            'page_size' => [
                'type'  => 'number',
                'value' => intval(config('maccms.page_size')),
            ],
            'api_search' => [
                'type'   => 'select',
                'value'  => config('maccms.api_search'),
                'option' => ['off'=>lang('close'),'s2t'=>lang('mac_api_search_s2t'),'t2s'=>lang('mac_api_search_t2s')],
                'tips'   => lang('mac_api_search_placeholder'),
            ],
            'html_hr'   => [
                'type'  => 'html',
                'value' => '<hr>'
            ],
            'filter_play' => [
                'type'  => 'text',
                'value' => config('maccms.filter_play'),
            ],
            'filter_tid' => [
                'type'  => 'text',
                'value' => config('maccms.filter_tid'),
            ],
            'filter_ids' => [
                'type'  => 'textarea',
                'value' => config('maccms.filter_ids'),
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
        return $this->fetch('maccms@config/index');
	}
    
    //保存配置
    public function update()
    {
        $status = \daicuo\Op::write(input('post.'),'maccms', 'config', 'system', 0, 'yes');
		if( !$status ){
		    $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
    
    //手动执行升级脚本
    public function upgrade()
    {
        controller('maccms/Sql','event')->upgrade();
        
        $this->success(lang('success'), 'store/index');
	}
}