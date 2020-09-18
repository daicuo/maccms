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
        if( $api_url = input('apiurl/u') ){
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
            //该资源站没有添加的分类才过行添加
            $data = array();
            $data['term_name'] = $post['type_name'][$type_id];
            $data['term_module'] = 'maccms';
            $data['term_much_type'] = 'category';
            $data['term_tpl'] = 'index';
            $data['term_api_url'] = $post['api'];//api_url
            $data['term_api_tid'] = $type_id;//type_id
            //不同资源站相同分类名称时增加随机后缀
            if( list_search($types, ['term_name'=>$data['term_name']]) ){
                $data['term_name'] = $data['term_name'].md5($post['api']);
            }
            $status[$key] = \daicuo\Term::save($data);
        }
        return json($status);
    }
    
    public function test(){
        $types = categoryItem();
        dump(list_search($types, ['term_api_url'=>'https://cj.okzy.tv/inc/api.php']));
        dump($types);
    }
    
    public function site_add(){
        $post = array();
        $post['api_type'] = input('post.api_type/s');
        $post['api_url'] = input('post.api_url/s');
        $result = apiAdd($post['api_url']);
        foreach($result['list'] as $key=>$value){
            $category = array();
            $category['term_name'] = $value['type_name'];
            $category['term_module'] = 'maccms';
            $category['term_much_type'] = 'category';
            $category['term_tpl'] = 'index';
            $category['term_api_type'] = $result['type'];//xml|json
            $category['term_api_url'] = $result['api'];//api_url
            $category['term_api_tid'] = $value['type_id'];//type_id
            $status = \daicuo\Term::save($category);
            dump($status);
        }
        //$status = \daicuo\Term::save($category);
        //dump(config('daicuo.error'));
        //dump($status);
        dump($category);
        dump($result);
        $this->assign('themes', $themes);
        //return $this->fetch('maccms@admin/site');
	}
}