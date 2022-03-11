<?php
namespace app\maccms\event;

use app\common\controller\Addon;

class Admin extends Addon
{
	
	public function _initialize()
    {
		parent::_initialize();
	}
    
    //管理首页
	public function index()
    {
        $themes = DcThemeOption('maccms');
        
        $items = [
            'theme' => [
                'type'   =>'select', 
                'value'  => config('maccms.theme'), 
                'option' => $themes,
            ],
            'theme_wap' => [
                'type'   => 'select',
                'value'  => config('maccms.theme_wap'),
                'option' => $themes,
            ],
            'site_title' => [
                'type'  => 'text', 
                'value' => config('maccms.site_title'),
            ],
            'site_keywords' => [
                'type'  => 'text', 
                'value' => config('maccms.site_keywords'),
            ],
            'site_description' => [
                'type'  => 'text',
                'value' => config('maccms.site_description'),
            ],
            'limit_index' => [
                'type'  => 'number',
                'value' => intval(config('maccms.limit_index')),
            ],
            'limit_categorys' => [
                'type'  => 'number',
                'value' => intval(config('maccms.limit_categorys')),
            ],
            'html_hr2'   => [
                'type'  => 'html',
                'value' => '<hr>'
            ],
            'rewrite_index' => [
                'type'        => 'text', 
                'value'       => config('maccms.rewrite_index'),
                'placeholder' => '',
                'tips'        => 'maccms$',
            ],
            'rewrite_play' => [
                'type'        => 'text',
                'value'       => config('maccms.rewrite_play'),
                'placeholder' => '',
                'tips'        => '[:from] [:id] [:ep] [:termId] [:termSlug]',
            ],
            'rewrite_category' => [
                'type'        => 'text', 
                'value'       => config('maccms.rewrite_category'),
                'placeholder' => '',
                'tips'        => '[:id] [:slug] [:pageNumber]',
            ],
        ];
        foreach($items as $key=>$value){
            $items[$key]['title']       = lang('mac_'.$key);
            if(!isset($value['placeholder'])){
                $items[$key]['placeholder'] = lang('mac_'.$key.'_placeholder');
            }
        }
        //
        $this->assign('items', DcFormItems($items));
        return $this->fetch('maccms@admin/index');
	}
    
     //保存配置
    public function update()
    {
        $post = input('post.');
        
        $status = \daicuo\Op::write($post,'maccms', 'config', 'system', 0, 'yes');
		if( !$status ){
		    $this->error(lang('fail'));
        }
        
        $this->rewriteRoute($post);
        
        $this->success(lang('success'));
	}
    
    //配置伪静态
    private function rewriteRoute($post)
    {
        //批量删除路由伪静态
        \daicuo\Op::delete_all([
            'op_name'     => ['eq','site_route'],
            'op_module'   => ['eq','maccms'],
        ]);
        //批量添加路由伪静态
        $result = \daicuo\Route::save_all([
            [
                'rule'        => $post['rewrite_index'],
                'address'     => 'maccms/index/index',
                'method'      => '*',
                'op_module'   => 'maccms',
            ],
            [
                'rule'        => $post['rewrite_play'],
                'address'     => 'maccms/play/index',
                'method'      => '*',
                'op_module'   => 'maccms',
            ],
            [
                'rule'        => $post['rewrite_category'],
                'address'     => 'maccms/category/index',
                'method'      => '*',
                'op_module'   => 'maccms',
            ],
        ]);
        //清理全局缓存
        DcCache('route_all', null);
    }
}