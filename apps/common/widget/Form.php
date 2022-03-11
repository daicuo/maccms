<?php
namespace app\common\widget;

use think\Controller;

class Form extends Controller
{
    //生成表单(bootstrap4)
    public function build($args=[])
    {
        $form = array();
        $form['name']           = '';//表单名称用于钩子判断
        $form['action']         = '#';//表单提交地址
        $form['method']         = 'post';//表单提交方法
        $form['class']          = 'bg-white';//表单样式表
        $form['target']         = '_self';//窗口打开方式
        $form['submit']         = lang('submit');//提交按钮名称
        $form['submit_class']   = 'btn btn-purple';//提交按钮CLASS
        $form['reset']          = '';//重置按钮名称
        $form['reset_class']    = 'btn btn-info';//重置按钮CLASS
        $form['close']          = '';//关闭按钮名称
        $form['close_class']    = 'btn btn-secondary';//关闭按钮CLASS
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
        //编辑器扩展
        if(config('common.editor_module') != 'textarea'){
            $form['view']['editor'] = DcEditorPath();
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
    
    //生成表格筛选表单字段
    public function filter($args=[])
    {
        $this->assign('view', config('form_view'));
        
        $this->assign('items', $args);
        
        return $this->fetch('common@form/filter'); 
    }
}