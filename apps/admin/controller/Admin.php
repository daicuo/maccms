<?php
namespace app\admin\controller;

use app\common\controller\Base;

/**
 * 独立后台公共控制器
 * @package app\admin\controller
 */
class Admin extends Base
{
    //系统权限属性
    protected $auth = [
        'check'       => true,
        'rule'        => '',
        'none_login'  => ['admin/index/login','admin/index/logout'],
        'none_right'  => [],
        'error_login' => 'admin/index/login',
        'error_right' => 'admin/index/login',
    ];
    
    /**
    * 继承初始化方法
    */
    protected function _initialize()
	{
        // 继承上级
        parent::_initialize();
        // 权限规则
        $this->auth['rule'] = $this->authRule();
        // 权限验证
        $this->_authCheck();
        // 网站日志
        $this->logInsert();
        // 模板路径
        $this->site['path_view']  = 'apps/admin/view/';
        // 菜单高亮值
        if($this->site['controll']=='addon'){
            $this->site['active'] = $this->query['module'].'/'.$this->query['controll'].'/'.$this->query['action'];
        }else{
            $this->site['active'] = $this->site['module'].'/'.$this->site['controll'].'/'.$this->site['action'];
        }
        // 父级元素高亮值
        if($this->query['parent']){
            $this->site['active'] .= '?'.http_build_query($this->query);
        }
        // 后台钩子
        \think\Hook::listen('hook_admin_init', $this->site);
        // 模板标签
        $this->assign($this->site);
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
        //config('common.validate_token', true);
        
        $this->assign('query', $this->query);
        
        $this->assign('fields', $this->formFields('create', $this->fields($this->query)));

		return $this->fetch();
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
        
        return $this->fetch();
    }
    
    /**
    * 默认查询操作
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
        $this->assign('fields', $this->formFields('index',$fields));
        //表格列字段
        $this->assign('columns', DcTableColumns($fields));
        //加载模板
		return $this->fetch();
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
            //$fields[$key]['class_tips']  = '';
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
        //插件管理单独调用
        if($this->site['controll'] == 'addon'){
            return false;
        }
        //只记录指定操作
        if(!$siteLog = config('common.site_log')){
            return false;
        }
        if(!in_array($this->site['action'],explode(',',$siteLog))){
            return false;
        }
        $data = [];
        $data['log_user_id']  = $this->site['user']['user_id'];
        $data['log_info_id']  = 0;
        $data['log_module']   = $this->site['module'];
        $data['log_controll'] = $this->site['controll'];
        $data['log_action']   = $this->site['action'];
        $data['log_type']     = 'adminLogs';//固定值(后台管理日志 )
        $data['log_ip']       = $this->request->ip();
        $data['log_name']     = $this->site['module'].'/'.$this->site['controll'].'/'.$this->site['action'];
        $data['log_info']     = $this->request->header('user-agent');
        return model('common/Log','loglic')->save($data);
    }
    
    //定义后台的权限验证规则
    private function authRule()
    {
        if($this->site['controll'] == 'addon'){
            $module   = $this->query['module'];
            $controll = $this->query['controll'];
            $action   = $this->query['action'];
        }else{
            $module   = $this->site['module'];
            $controll = $this->site['controll'];
            $action   = $this->site['action'];
        }
        $action = str_replace(['create','edit'],'index',$action);
        $action = str_replace(['update','status'],'save',$action);
        $action = str_replace(['clear'],'delete',$action);
        return $module.'/'.$controll.'/'.$action;
    }
}