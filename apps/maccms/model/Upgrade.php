<?php
namespace app\maccms\model;

use app\common\loglic\Update;

class Upgrade extends Update
{
    public function init()
    {
        //升级框架结构
        //model('common/Update18','loglic')->upgrade();
        
        //升级NAVBAR
        $this->navs();
        
        //升级分类
        $this->category();
    }
    
    //升级navbar
    public function navs()
    {
        $list = DcTermSelect([
            'type' => 'navs',
        ]);
        //字段映射
        $navs = [];
        foreach($list as $key=>$value){
            $navs[$key]['term_id']       = $value['term_id'];
            $navs[$key]['term_parent']   = $value['term_parent'];
            $navs[$key]['term_name']     = $value['term_name'];
            $navs[$key]['term_slug']     = $value['navs_url'];
            $navs[$key]['term_type']     = str_replace(['navs','links'],['nav','link'],$value['term_action']);
            $navs[$key]['term_info']     = $value['term_info'];
            $navs[$key]['term_title']    = $value['navs_active'];
            $navs[$key]['term_keywords'] = $value['navs_ico'];
            $navs[$key]['term_description'] = $value['navs_image'];
            $navs[$key]['term_status']   = $value['term_status'];
            $navs[$key]['term_order']    = $value['term_order'];
            $navs[$key]['term_action']   = $value['navs_target'];
            $navs[$key]['term_controll'] = 'navs';
            $navs[$key]['term_module']   = $value['term_module'];
        }
        //批量更新
        $result = dbUpdateAll('term',$navs);
        //删除无用
        $result = db('termMeta')->where(['term_meta_key'=>['in',['navs_url','navs_image','navs_class','navs_active','navs_target']]])->delete();
        //返回结果
        return $result;
    }
    
    //转换分类
    public function category()
    {
        \think\Db::execute("update dc_term set term_controll='category' where term_type='category' and term_module='maccms';");
        
        \think\Db::execute("update dc_term set term_type='navbar' where term_controll='category' and term_module='maccms';");
    }
}