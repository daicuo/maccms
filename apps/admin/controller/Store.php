<?php
namespace app\admin\controller;

use app\common\controller\Admin;

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
    
    // 应用市场API
	public function index()
    {
        $service = new \daicuo\Service();
        
        $data = $service->apiData($this->query);
        
        if($this->request->isAjax()){
            //已安装应用列表
            $apps = controller('common/Apply','event')->appsInfo();
            //数据处理
            foreach($data['list'] as $key=>$value){
                $operate = array();
                $operate['demo']     = '<a class="btn btn-sm btn-outline-secondary" href="'.$value['demo'].'" target="_blank"><i class="fa fa-eye mr-1"></i>'.lang('demo').'</a>';
                $operate['forum']    = '<a class="btn btn-sm btn-secondary" href="'.$value['forum'].'" target="_blank"><i class="fa fa-comment mr-1"></i>'.lang('forum').'</a></div><div class="btn-group">';
                $operate['down']     = '<a class="btn btn-sm btn-outline-purple" href="'.DcUrl('apply/down',['module'=>$value['module'],'version'=>$value['version']]).'" target="_blank"><i class="fa fa-download mr-1"></i>'.lang('down').'</a>';
                if($apps[$value['module']]){
                    $operate['delete']  = '<a class="btn btn-sm btn-danger" href="'.DcUrl('apply/delete',['module'=>$value['module']]).'" data-toggle="delete"><i class="fa fa-trash-o mr-1"></i>'.lang('delete').'</a>';
                    if($apps[$value['module']]['version'] != $value['version']){
                        $operate['upgrade']  = '<a class="btn btn-sm btn-outline-purple" href="'.DcUrl('apply/upgrade',['module'=>$value['module']]).'" data-toggle="get"><i class="fa fa-arrow-up mr-1"></i>'.lang('upgrade').'</a>';
                    }
                }else{
                    $operate['install']  = '<a class="btn btn-sm btn-purple" href="'.DcUrl('apply/install',['module'=>$value['module'],'version'=>$value['version']]).'" data-toggle="get"><i class="fa fa-cloud-download mr-1"></i>'.lang('install').'</a>';
                }
                $data['list'][$key]['operate'] = '<div class="btn-group mr-1">'.implode('',$operate).'</div>';
            }
            return json($data['list']);
        }
        
        $this->assign('category', $data['category']);
        
        $this->assign('list', $data['list']);
        
        $this->assign('query', $this->query);
        
		return $this->fetch();
	}
	
}