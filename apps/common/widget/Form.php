<?php
namespace app\common\widget;

use think\Controller;

class Form extends Controller
{
  
    public function build($params){
        $form = array();
        $form['action']   = '#';
        $form['method']   = 'post';
        $form['class']    = 'bg-white';
        $form['ajax']     = true;
        $form['submit']   = lang('submit');//提交按钮
        $form['reset']    = '';//重置按钮
        $form['close']    = '';//弹窗关闭按钮
        $form['items']    = [];//表单元素列表
        $form['group']    = [];//表单组
        $form['disabled'] = false;//禁用
        $form['callback'] = '';//AJAX回调
        $form['data']     = '';//默认数据
        \think\Hook::listen('hook_build_form', $params);
        $this->assign(array_merge($form, $params));
        return $this->fetch(APP_PATH.'common'.DS.'view'.DS.'daicuo_form.tpl'); 
    }

}