<?php
namespace app\maccms\controller;

use app\common\controller\Front;

class Detail extends Front{

    //继承
    public function _initialize(){
        parent::_initialize();
    }

    //详情页
    public function index(){
        $term  = array();
        $tid   = input('tid/d',0);   //分类ID
        $id    = input('id/d',51930);//视频ID
        //查询已绑定的apiTid
        if($tid){
            $term = categoryId($tid);
        }else{
            $term['term_id'] = 0;
        }
        if($term['term_id']){
            $info = apiDetail($id, $term['term_api_url'], $term['term_api_type'] );
        }else{
            $info = apiDetail($id);
        }
        $this->assign(array_merge($info, $term));
        return $this->fetch('detail/index');
    }
    
}