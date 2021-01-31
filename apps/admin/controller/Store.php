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
                if($apps[$value['module']]){
                    if($apps[$value['module']]['version'] != $value['version']){
                        $operate['upgrade']  = '<a class="btn btn-sm btn-primary mr-1" href="'.DcUrl('apply/upgrade',['module'=>$value['module']]).'" data-toggle="get"><i class="fa fa-arrow-up mr-1"></i>'.lang('upgrade').'</a>';
                    }
                    $operate['delete']  = '<a class="btn btn-sm btn-danger mr-1" href="'.DcUrl('apply/delete',['module'=>$value['module']]).'" data-toggle="delete"><i class="fa fa-trash-o mr-1"></i>'.lang('delete').'</a>';
                }else{
                    $operate['install']  = '<a class="btn btn-sm btn-success mr-1" href="'.DcUrl('apply/install',['module'=>$value['module'],'version'=>$value['version']]).'" data-toggle="get"><i class="fa fa-cloud-download mr-1"></i>'.lang('install').'</a>';
                }
                $operate['down']     = '<a class="btn btn-sm btn-secondary mr-1" href="'.DcUrl('apply/down',['module'=>$value['module'],'version'=>$value['version']]).'" target="_blank"><i class="fa fa-download mr-1"></i>'.lang('down').'</a>';
                $operate['forum']    = '<a class="btn btn-sm btn-purple mr-1" href="'.$value['forum'].'" target="_blank"><i class="fa fa-comment mr-1"></i>'.lang('forum').'</a>';
                $operate['demo']     = '<a class="btn btn-sm btn-info" href="'.$value['demo'].'" target="_blank"><i class="fa fa-flash mr-1"></i>'.lang('demo').'</a>';
                $data['list'][$key]['operate'] = implode('',$operate);
            }
            return json($data['list']);
        }
        
        $this->assign('category', $data['category']);
        
        $this->assign('list', $data['list']);
        
        $this->assign('query', $this->query);
        
		return $this->fetch();
	}
	
}