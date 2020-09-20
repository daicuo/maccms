<?php
namespace app\maccms\controller;

use app\common\controller\Front;

/*
** MacCms 主资源站
*/

class Api extends Front
{

    protected $term = '';

    //继承
    public function _initialize(){
        parent::_initialize();
        if( $tid = input('tid/d', 0) ){
            $this->term = categoryMeta('term_api_tid', $tid );
        }else{
            $this->error('您还没有指定该分类的API接口来源，请先在后台分类管理添加');
        }
    }
    
    //首页
    public function index(){
        
    }
    
    //分类页 maccms/api/type?tid=12
    public function type(){
        //跳转至绑定的分类ID
        if( $this->term['term_id'] ){
            $this->redirect('maccms/category/index', ['id'=>$this->term['term_id']]);
            exit();
        }
        $id   = input('id/d');
        $page = input('page/d',1);
        $term = categoryMeta('term_api_tid', $id);//数据库绑定分类
        $list = apiItem(['t'=>$id, 'pg'=>$page], $term['term_api_url'], $term['term_api_type']);//远程数据
        $this->assign($term);
        $this->assign($list['page']);
        $this->assign('item', $list['item']);
        if($this->request->isAjax()){
            return $this->fetch('type_ajax');
        }
        return $this->fetch('type');
    }
    
    //搜索页
    public function search(){
        
    }
    
    //详情页 maccms/api/detail?tid=12&id=182
    public function detail(){
        //跳转至绑定的分类ID
        if( $this->term['term_id'] ){
            $this->redirect('maccms/detail/index', ['tid'=>$this->term['term_id'],'id'=>input('id/d',1)]);
            exit();
        }
        $tid   = input('tid/d',0);   //分类ID
        $id    = input('id/d',51930);//视频ID
        //远程数据
        if($tid){
            $term = categoryMeta('term_api_tid', $tid);
            $info = apiDetail($id, $term['term_api_url'], $term['term_api_type'] );
        }else{
            $info = apiDetail($id);
        }
        if($term){
            $info = array_merge($info, $term);
        }
        $this->assign($info);
        return $this->fetch('detail/index');
    }
    
    //播放页 maccms/api/play?tid=12&id=182&ep=77&from=play0
    public function play(){
        $tid   = input('tid/d',0);   //分类ID
        $id    = input('id/d',51930);//视频ID
        $ep    = input('ep/d',51930)-1;//视频集数
        $from  = input('from/s','play0');//播放器组
        //跳转至绑定的分类ID
        if( $this->term['term_id'] ){
            $this->redirect('maccms/play/index', ['tid'=>$this->term['term_id'],'id'=>input('id/d',1),'ep'=>$ep,'form'=>$from]);
            exit();
        }
        //远程数据
        if($tid){
            $term = categoryMeta('term_api_tid', $tid);
            $info = apiDetail($id, $term['term_api_url'], $term['term_api_type'] );
        }else{
            $info = apiDetail($id);
        }
        //play标签
        if($info['play_list'][$from]){
            $info['play_from'] = $from;
            foreach($info['play_list'][$from][$ep] as $key=>$value){
               $info[$key] = $value;
            }
        }
        //播放器
        $info['vod_player'] = DcPlayer(['type'=>$info['play_from'],'url'=>$info['play_url']]);
        if($term){
            $info = array_merge($info, $term);
        }
        $this->assign($info);
        return $this->fetch('play/index');
    }
    
    //空操作
    public function _empty($name){
    }
  
}