<?php
namespace app\common\event;

/**
 * 控制器分层.应用
 */
class Apply{
    
    /**
     * 加载框架所有应用信息/函数/语言
     */
    public function appBegin(){
        //加载所有己安装应用的全局信息
        foreach(config('common.site_applys') as $key=>$value){
            //加载插件动态配置
            action('common/Op/config', $key, 'event');
            //加载插件全局语言包
            \think\Lang::load(APP_PATH.$key.'/common/'.config('default_lang').'.php');
            //加载插件全局函数
            include(APP_PATH.$key.'/common/function.php');//\think\Loader::import('function', APP_PATH.'home/common/', EXT);
        }
    }
    
    //获取系统所有应用列表包含安装状态
    public function appsInfo(){
        $applys = config('common.site_applys');
        if(is_null($applys)){
           $applys = array(); 
        }
		//以本地模块为应用信息
		$dirs = DcAdminApply();
		foreach($dirs as $module){
            $apply = DcLoadConfig(APP_PATH.$module.'/common/info.php', 'apply');
            if($applys[$module]){
                $applys[$module]['install'] = true;
            }else{
                $apply['apply']['install'] = false;
                $applys = array_merge($applys, [$module=>$apply]);
            }
		}
        return $applys;
    }
    
    /**
    * 卸载插件/先加载插件自身卸载脚本再删除安装记录
    * @return bool
    */
    public function uninstall($module=''){
        if($module){
            //执行插件自卸载脚本
            $status = action($module.'/Sql/'.unInstall,'','event');
            if(!$status){
                config('daicuo.error', lang('apply_fail_sql'));
                return false;
            }
            //删除插件安装记录
            $where = array();
            $where['op_name'] = array('eq', 'site_applys');
            $where['op_module'] = array('eq', 'common');
            $status = \daicuo\Op::delete_value_key($where, $module);
            if(!$status){
                config('daicuo.error', lang('apply_fail'));
                return false;
            }
            //删除全局缓存
            DcCache('route_all', null);
            DcCache('hook_all', null);
            DcCache('config_'.$module, null);
            DcCache('config_common', null);
            //\think\Cache::clear();
            return true;
        }
        return false;
    }
    
    //安装插件 自动写入应用的相关信息至config.applys
    public function install($module){
        if(!$module){
            return false;
        }
        //导入待安装的插件信息
        DcLoadConfig(APP_PATH.$module.'/common/info.php', 'apply');
        //读取已安装的插件列表
        $applys = config('common.site_applys');
        //数据库依赖验证
        if( config('apply.datatype') ){
            if( !in_array(config('database.type'), config('apply.datatype')) ){
                config('daicuo.error', lang('apply_daicuo_datatype'));
                return false;
            }
        }
        //版本依赖验证
        $version = new \daicuo\Version();
        foreach(config('apply.rely') as $key=>$value){
            if($key == 'daicuo'){
                if( !$version->check(config('daicuo.version'), $value) ){
                    config('daicuo.error', lang('apply_daicuo'));
                    return false;
                }
            }else{
                if(is_null($applys[$key])){
                    config('daicuo.error', DcHtml($key).lang('apply_uninstall'));
                    return false; 
                }else{
                    if( !$version->check($applys[$key]['version'], $value) ){
                        config('daicuo.error', lang('apply_daicuo'));
                        return false;
                    }
                }
            }
        }
        //执行插件自安装脚本
        $status = action($module.'/Sql/'.install,'','event');
        if(!$status){
            config('daicuo.error', lang('apply_fail_sql'));
            return false;
        }
        //安装应用信息至数据库
        $status = $this->write( config('apply') );
        if(!$status){
            config('daicuo.error', lang('apply_fail'));
            return false;
        }
        //删除全局缓存并返回结果
        DcCache('route_all', null);
        DcCache('hook_all', null);
        DcCache('config_common', null);
        return $status;
    }

    /**
    * 自动处理新增与修改
    * @param array $data 数据
    * @return array 数据集
    */
	public function write($data=[]){
        //必要字段验证
        if(false === DcCheck($data, 'common/Apply')){
			return false;
		}
		//读取旧数据
        $info = \daicuo\Op::get(['op_name' => 'site_applys', 'op_module' => 'common'], false);
        //无记录则新增
        if(is_null($info)){
            $op = array();
            $op['op_name'] = 'site_applys';
            $op['op_value'] = [$data['module']=>$data];
            $op['op_module'] = 'common';
            $op['op_controll'] = NULL;
            $op['op_action'] = NULL;
            $op['op_order'] = 0;
            $op['op_autoload'] = 'yes';
            return \daicuo\Op::save($op);
        }
		//新与旧合并
        $info = $info->toArray();
        $info['op_value'] = array_merge($info['op_value'], [$data['module']=>$data]);
		return \daicuo\Op::update_id($info['op_id'], $info);
	}

}