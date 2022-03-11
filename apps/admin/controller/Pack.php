<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

//应用打包
class Pack extends Admin
{
    public function index()
    {
      return $this->fetch();
    }
    
    public function save()
    {
        $status = \daicuo\Op::write(input('post.'), 'common', 'config', 'system', 0, 'yes');
        if( !$status ){
            $this->error(lang('fail'));
        }
        $this->success(lang('success'));
    }
}