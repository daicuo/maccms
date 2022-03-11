<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Store extends Admin
{
    /**
    * 继承初始化方法
    */
    public function _initialize()
    {
        //移除HTML标签
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        // 继承上级
        parent::_initialize();
    }
    
    //应用市场API
    public function index()
    {
        $service = new \daicuo\Service();
        
        $data = $service->apiData($this->query);
        
        if($this->request->isAjax()){
            //本地应用列表
            $apps = \daicuo\Apply::appsInfo();
            //数据处理
            foreach($data['list'] as $key=>$value){
                $operate = array();
                $operate['down']     = '<a class="btn btn-sm btn-outline-purple" href="'.DcUrl('apply/down',['module'=>$value['module'],'version'=>$value['version']]).'" target="_blank"><i class="fa fa-download mr-1"></i>'.lang('down').'</a>';
                if($apps[$value['module']]){
                    if( version_compare($apps[$value['module']]['version'], $value['version'], '<') ){
                        $operate['upgrade']  = '<a class="btn btn-sm btn-danger" href="'.DcUrl('apply/upgrade',['module'=>$value['module']]).'" data-toggle="get"><i class="fa fa-arrow-up mr-1"></i>'.lang('upgrade').'</a>';
                    }else{
                        $operate['upgrade']  = '<a class="btn btn-sm btn-purple disabled" href="javascript:;"><i class="fa fa-arrow-up mr-1"></i>'.lang('upgrade').'</a>';
                    }
                }else{
                    $operate['install']  = '<a class="btn btn-sm btn-purple" href="'.DcUrl('apply/install',['module'=>$value['module'],'version'=>$value['version']]).'" data-toggle="get"><i class="fa fa-cloud-download mr-1"></i>'.lang('install').'</a>';
                }
                $data['list'][$key]['operate'] = '<div class="btn-group mr-1">'.implode('',$operate).'</div>';
                $data['list'][$key]['demo'] = '<a class="text-muted" href="'.$value['demo'].'" target="_blank"><i class="fa fa-camera"></i></a>';
            }
            return json($data);
        }
        
        $this->assign('category', $data['category']);
        
        $this->assign('list', $data['list']);
        
        $this->assign('query', $this->query);
        
        return $this->fetch();
    }
	
}