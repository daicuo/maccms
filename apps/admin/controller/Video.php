<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Video extends Admin
{
    /**
     * 批量更新与新增配置
     * @return array
     */	
    public function update()
    {
        $data = input('post.');
        if(is_null($data['video_in'])){
            $data['video_in'] = 'off';
        }
        $status = \daicuo\Op::write($data, 'common');
        if( !$status ){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
    }
		
}