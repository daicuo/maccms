<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Video extends Admin
{
    public function update()
    {
        $post = input('post.');
        $post['video_in'] = DcSwitch($post['video_in']);
        //保存配置
        $result = \daicuo\Op::write($post,'common','config','system','0','yes');
        if( !$result ){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
    }
    
    public function index()
    {
        $this->assign('fields', DcFormItems(model('admin/Video','loglic')->fields()));
        
        $this->assign('query', $this->query);
        
        return $this->fetch();
    }
}