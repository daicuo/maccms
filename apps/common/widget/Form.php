<?php
namespace app\common\widget;

use think\Controller;

class Form extends Controller
{
  
    public function build($args)
    {
        $form = array();
        $form['name']           = '';//表单名称用于钩子判断
        $form['action']         = '#';//表单提交地址
        $form['method']         = 'post';//表单提交方法
        $form['class']          = 'bg-white';//表单样式表
        $form['target']         = '_self';//窗口打开方式
        $form['submit']         = lang('submit');//提交按钮
        $form['reset']          = '';//重置按钮
        $form['close']          = '';//关闭按钮
        $form['disabled']       = false;//是否禁用
        $form['ajax']           = true;//AJAX操作
        $form['ajax_callback']  = '';//AJAX回调
        $form['data']           = '';//默认数据
        $form['items']          = [];//表单元素列表
        $form['group']          = [];//表单组
        $form['class_tabs']     = '';
        $form['class_link']     = '';
        $form['class_content']  = '';
        $form['view']           = config('form_view');//表单元素模板路径
        //预留钩子
        \think\Hook::listen('form_build', $args);
        //参数合并
        $form = array_merge($form, $args);
        //添加表单token
        if( config('common.validate_token') ){
            array_push($form['items'],[
                'type' => 'hidden',
                'name' => '__token__',
                'value' => $this->request->token(),
            ]);
        }
        //是否分组表单
        if($form['group']){
            $tplPath = 'common@form/group';
        }else{
            $tplPath = 'common@form/index';
        }
        //赋值模板变量
        $this->assign($form);
        //释放内存
        unset($form);unset($args);
        //模板渲染
        return $this->fetch($tplPath); 
        //return $this->fetch(APP_PATH.'common'.DS.'view'.DS.'daicuo_form.tpl'); 
    }

}