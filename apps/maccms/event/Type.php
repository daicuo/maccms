<?php
namespace app\maccms\event;

use app\common\controller\Addon;

class Type extends Addon
{
	
	public function _initialize()
    {
		parent::_initialize();
	}
    
    //同步资源站分类
    public function index()
    {
        if( $api_url = input('apiurl/s') ){
            $bindItem = model('maccms/Client')->bind($api_url);
        }
        //保存配置
        if($bindItem){
            \daicuo\Op::write(['api_url'=>$bindItem['api'],'api_type'=>$bindItem['type']], 'maccms', 'config', 'system', 0, 'yes');
        }
        //加载模板
        $this->assign($bindItem);
        return $this->fetch('maccms@type/index');
	}
    
    //将资源站分类添加到框架分类里
    public function update()
    {
        //系统已存在的分类
        $types  = categoryItem([
            'cache' => false
        ]);
        //提交的资源站分类
        $post   = input('post.');
        //添加结果
        $status = [];
        foreach($post['type_id'] as $key=>$type_id){
            //添加前检测是否已添加
            if( $term = list_search($types, ['term_api_tid'=>$type_id]) ){
                $status[$key] = $post['type_name'][$type_id].'已添加过，不需要再次添加';
                continue;
            }
            //该资源站没有添加的分类才添加
            $data = array();
            $data['term_name']     = $post['type_name'][$type_id];
            $data['term_module']   = 'maccms';
            $data['term_type']     = 'category';
            $data['term_slug']     = '';
            $data['term_navbar']   = 'yes';
            $data['term_api_tid']  = $type_id;
            $status[$key] = \daicuo\Term::save($data);
        }
        return json($status);
    }
}