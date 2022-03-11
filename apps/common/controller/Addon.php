<?php
namespace app\common\controller;

use think\Controller;

/**
 * 插件后台公共控制器(继承此文件)
 * @package app\common\controller
 */
class Addon extends Controller
{
    // 当前用户
    protected $user = [];
    
    // 地址栏参数
    protected $query = [];
    
    //继承上级
    protected function _initialize()
    {
        $this->user = \daicuo\User::get_current_user();
        
        $this->query = $this->request->param();
        
        $this->logInsert();
        
        parent::_initialize();
    }
    
    //定义表单、表格字段列表
    protected function fields($data=[])
    {
        return [];
    }
    
    //定义表单字段初始数据
    protected function formData()
    {
        return [];
    }
    
    //定义表格数据列表（JSON格式）
    protected function ajaxJson()
    {
        return [];
    }
    
    /**
    * 默认新增操作
    * @return mixed
    */
    public function create()
    {
        $this->assign('query', $this->query);
        
        $this->assign('fields', $this->formFields('create', $this->fields($this->query)));

        return $this->fetch('common@addon/create');
    }
    
    /**
    * 默认修改操作
    * @return mixed
    */
    public function edit()
    {
        if( !$data=$this->formData() ){
            $this->error(lang('empty'));
        }
        
        $this->assign('data',  $data);
        
        $this->assign('query', $this->query);
        
        $this->assign('fields', $this->formFields('edit', $this->fields($data)));
        
        return $this->fetch('common@addon/edit');
    }
    
    /**
    * 默认插件管理
    * @return mixed
    */
    public function index()
    {
        //AJAX表格请求
        if( $this->request->isAjax() ){
            return json($this->ajaxJson());
		}
        //表单字段
        $fields = $this->fields($data);
        //地址栏参数
        $this->assign('query', $this->query);
        //表单筛选字段
        $this->assign('fields', $this->formFields('index', $fields));
        //表格列字段
        $this->assign('columns', DcTableColumns($fields));
        //加载模板
		return $this->fetch($this->query['module'].'@'.$this->query['controll'].'/index');
    }
    
     /**
    * 生成表单字段通用属性
    * @version 1.7.0 首次引入
    * @param string $action 必需;操作名(create|edit|index);默认:create
    * @param array $fields 必需;多维数组的表单字段格式;默认：空
    * @return array 表单字段数组列表（DcBuildForm）
    */
    protected function formFields($action='create', $fields=[])
    {
        //增加字段的相同属性值
        foreach($fields as $key=>$value){
            if(!isset($fields[$key]['class_left'])){
                $fields[$key]['class_left'] = 'col-12';
            }
            if(!isset($fields[$key]['class_right'])){
                $fields[$key]['class_right'] = 'col-12';
            }
        }
        //表单字段（格式化）
        return DcFormItems($fields);
    }
    
    /**
     * 后台管理日志
     * @version 1.7.0 首次引入
     * @return mixed
     */
    protected function logInsert()
    {
        //只记录指定操作
        if(!$siteLog = config('common.site_log')){
            return false;
        }
        if(!in_array($this->query['action'],explode(',',$siteLog))){
            return false;
        }
        $data = [];
        $data['log_user_id']  = $this->user['user_id'];
        $data['log_info_id']  = 0;
        $data['log_module']   = $this->query['module'];
        $data['log_controll'] = $this->query['controll'];
        $data['log_action']   = $this->query['action'];
        $data['log_type']     = 'adminLogs';
        $data['log_ip']       = $this->request->ip();
        $data['log_name']     = $this->query['module'].'/'.$this->query['controll'].'/'.$this->query['action'];
        $data['log_info']     = $this->request->header('user-agent');
        return model('common/Log','loglic')->save($data);
    }
    
    //快速批量保存插件配置(key=>value样式)
    protected function write()
    {
        $status = \daicuo\Op::write(
            input('post.'),
            input('module/s','addon'), 
            input('controll/s','config'),
            input('action/s','system'),
            input('order/d',0),
            input('autoload/s','yes')
        );
		if(!$status){
		    $this->error(lang('fail'));
        }
        $this->success(lang('success'));
	}
}