<?php
namespace app\maccms\event;

use think\Controller;

/*
** 资源站分类同步
*/

class Type extends Controller
{
	
	public function _initialize(){
		parent::_initialize();
	}
    
    //同步资源站分类
    public function index(){
        if( $api_url = input('apiurl/s') ){
            $bindItem = controller('maccms/Client', 'event')->bind($api_url);//type api list
        }
        $this->assign($bindItem);
        return $this->fetch('maccms@type/index');
	}
    
    //将资源站分类添加到框架分类里
    public function update(){
        $types  = categoryItem();//系统已存在的分类
        $post   = input('post.');//提交的资源站分类
        $status = [];//添加结果
        foreach($post['type_id'] as $key=>$type_id){
            //添加前检测是否已添加
            if( $term = list_search($types, ['term_api_tid'=>$type_id]) ){
                if( list_search($term, [ 'term_api_url'=>$post['api'] ]) ){
                    $status[$key] = $post['type_name'][$type_id].'已添加过，不需要再次添加';
                    continue;
                }
            }
            //该资源站没有添加的分类才添加
            $data = array();
            $data['term_name'] = $post['type_name'][$type_id];
            $data['term_module'] = 'maccms';
            $data['term_much_type'] = 'category';
            $data['term_tpl'] = 'index';
            $data['term_api_url'] = $post['api'];//api_url
            $data['term_api_type'] = $post['type'];//api_type
            $data['term_api_tid'] = $type_id;//type_id
            //不同资源站相同分类名称时增加随机后缀
            if( list_search($types, ['term_name'=>$data['term_name']]) ){
                $data['term_name'] = $data['term_name'].md5($post['api']);
            }
            $status[$key] = \daicuo\Term::save($data);
        }
        return json($status);
    }
}