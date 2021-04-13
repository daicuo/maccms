<?php
namespace app\common\event;

class Apply
{
    private $error = '';
    
    /**
     * 模块初始化
     * 加载框架所有应用信息/函数/语言
     */
    public function moduleInit($module)
    {
        //加载所有己安装插件应用的全局信息
        foreach(config('common.site_applys') as $key=>$value){
            //加载插件动态配置
            action('common/Op/config', $key, 'event');
            //加载插件初始信息等 当前模块会自动加载 防止二次加载
            if($key != $module){
                \think\Lang::load(APP_PATH.$key.'/lang/'.config('default_lang').'.php');//插件语言包
                \think\Config::load(APP_PATH.$key.'/config.php');//插件初始配置
                \think\Loader::import('common', APP_PATH.$key, EXT);//插件公用函数
                /*注册插件初始钩子,全局钩子请使用钩子模块添加到数据库中
                \think\Config::load(APP_PATH.$key.'/tags.php', 'applys_tags');
                foreach(config('applys_tags') as $key=>$value){
                    \think\Hook::add($key, $value[0], $value['_overlay']);
                }
                config('applys_tags', NULL);*/
            }
        }
    }
    
    //获取系统所有应用列表包含安装状态
    public function appsInfo()
    {
        $applys = config('common.site_applys');
        if(is_null($applys)){
           $applys = array(); 
        }
		//以本地模块为应用信息
		$dirs = DcAdminApply();
		foreach($dirs as $module){
            $apply = DcLoadConfig(APP_PATH.$module.'/info.php', 'apply');
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
    * 禁用/启用插件
    * @param string $module 应用模块名称
    * @param string $status 应用状态disable|enable
    * @return bool
    */
    public function updateStatus($module='', $status='enable')
    {
        if(!$module){
            $this->error = 'mustIn';
            return false;
        }
        //导入待安装的插件信息
        DcLoadConfig(APP_PATH.$module.'/info.php', 'apply');
        if( config('apply') ){
            //是否禁用
            if($status == 'disable'){
                config('apply.disable',true);
            }
            //更新插件列表
            $result = $this->write( config('apply') );
            if(!$result){
                $this->error = 'apply_fail_op';
                return false;
            }
            //删除全局缓存并返回结果
            DcCache('route_all', null);
            DcCache('hook_all', null);
            DcCache('config_common', null);
            return $result;
        }
        return false;
    }
    
    /**
    * 卸载插件/先加载插件自身卸载脚本再删除安装记录
    * @param string $module 应用模块名称
    * @param string $isDel 是否删除应用文件
    * @return bool
    */
    public function uninstall($module, $isDel = false)
    {
        if(!$module){
            $this->error = 'mustIn';
            return false;
        }
        //依赖插件禁止卸载
        if(config('common.apply_rely')){
            if( in_array($module, explode(',',config('common.apply_rely'))) ){
                $this->error = 'apply_fail_rely';//依赖插件禁止卸载
                return false;
            }
        }
        //执行插件自卸载脚本
        if( !$this->executeSql($module, 'uninstall') ){
            $this->error = 'apply_fail_sql';
            return false;
        }
        //删除插件安装记录
        $where = array();
        $where['op_name'] = array('eq', 'site_applys');
        $where['op_module'] = array('eq', 'common');
        $status = \daicuo\Op::delete_value_key($where, $module);
        if(!$status){
            $this->error = 'apply_fail_op';//更新插件列表出错
            return false;
        }
        //删除全局缓存
        DcCache('route_all', null);
        DcCache('hook_all', null);
        DcCache('config_'.$module, null);
        DcCache('config_common', null);
        //\think\Cache::clear();
        //删除目录包括下面的文件
        if(!in_array($module,['admin','api','common','index']) && $isDel){
            $module = DcDirPath($module);
            \files\Dir::delDir('./apps/'.$module);
        }
        return true;
    }
    
    /**
    * 安装与升级插件
    * @param string $module 应用模块名称
    * @param string $$method 脚本方法（install|upgrade）
    * @return bool
    */
    public function install($module, $method = 'install')
    {
        //必要参数
        if(!$module){
            $this->error = 'mustIn';
            return false;
        }
        //导入待安装的插件信息
        DcLoadConfig(APP_PATH.$module.'/info.php', 'apply');
        //读取已安装的插件列表
        $applys = config('common.site_applys');
        //数据库依赖验证
        if( config('apply.datatype') ){
            //数据库不匹配
            if( !in_array(config('database.type'), config('apply.datatype')) ){
                $this->error = 'apply_fail_datatype%store/index/?searchText=database';
                return false;
            }
        }
        //版本依赖验证
        $version = new \daicuo\Version();
        foreach(config('apply.rely') as $key=>$value){
            if($key == 'daicuo'){
                //验证框架版本
                if( !$version->check(config('daicuo.version'), $value) ){
                    $this->error = 'apply_fail_update_frame%index/index#update%'.implode(',',$value);
                    return false;
                }
            }else{
                //验证依赖插件
                if( is_null($applys[$key]) ){
                    //未安装依赖插件
                    $this->error = 'apply_fail_uninstall%store/index/?searchText='.$key.'%'.$key.implode(',',$value);
                    return false;
                }else{
                    //依赖插件版本过低
                    if( !$version->check($applys[$key]['version'], $value) ){
                        $this->error = 'apply_fail_update_app%store/index/?searchText='.$key.'%'.$key.implode(',',$value);
                        return false;
                    }
                }
            }
        }
        //执行安装或升级脚本
        if( !$this->executeSql($module, $method) ){
            return false;
        }
        //应用信息写入数据库
        $status = $this->write( config('apply') );
        if(!$status){
            $this->error = 'apply_fail_op';//更新插件列表出错
            return false;
        }
        //删除全局缓存并返回结果
        DcCache('route_all', null);
        DcCache('hook_all', null);
        DcCache('config_common', null);
        return $status;
    }
    
    /**
    * 在线安装应用
    * @param array $args 参数['name'=>'demo','version'=>'1.0.2']
    * @return bool
    */
    public function installOnline($args)
    {
        //必要参数
        if(!$args['module']){
            $this->error = 'mustIn';
            return false;
        }
        //在线安装参数标识
        $args['event'] = 'install';
        //待安装应用名
        $module = DcDirPath($args['module']);
        //应用目录
        $moduleDir = './apps/'.$module;
        //实例化文件类
        $file = new \files\File();
        //是否已存在相同应用
        if( $file->d_has($moduleDir) ){
            $this->error = 'apply_name_unique';
            return false;
        }
        //是否有权限创建应用目录
        if(!$file->d_create($moduleDir)){
            $this->error = 'apply_dir_access';
            return false;
        }
        //下载应用到临时目录
        $service = new \daicuo\Service();
        if(!$saveFile = $service->applyDownLoad($args)){
            $file->d_delete($moduleDir);
            $this->error = $service->getError();
            return false;
        }
        //在线解压到应用目录
        $zip = new \files\Zip();
        if(!$zip->unzip($saveFile, $moduleDir)){
            $file->d_delete($moduleDir);
            $file->f_delete($saveFile);
            $this->error = 'apply_unzip_failed';
            return false;
        }
        //执行安装脚本
        if(!$this->install($module, 'install')){
            $file->d_delete($moduleDir);
            $file->f_delete($saveFile);
            return false;
        }
        //默认返回
        return true;
    }
    
    /**
    * 在线升级应用（一个一个版本递增升级）
    * @param array $args 参数['name'=>'demo','version'=>'1.0.2']
    * @return bool
    */
    public function upgradeOnline($module)
    {
        //必要参数
        if(!$module){
            $this->error = 'mustIn';
            return false;
        }
        //已安装应用列表
        $applys = $this->appsInfo();
        //待升级应用名称
        $module = DcDirPath($module);
        //验证是否已安装
        if(!$applys[$module]){
            $this->error = 'apply_module_uninstall';
            return false;
        }
        //待升级应用旧版本
        $version = $applys[$module]['version'];
        //待升级应用目录
        $moduleDir = './apps/'.$module;
        //获取升级信息
        $service = new \daicuo\Service();
        $upJson = $service->apiUpgrade(['module'=>$module,'version'=>$version]);
        if(!$upJson['code']){
            $this->error = 'apply_update_server_error';
            return false;
        }
        if($upJson['version'] == $version){
            $this->error = 'apply_version_isnew';
            return false;
        }
        //下载应用到临时目录
        $service = new \daicuo\Service();
        if(!$saveFile = $service->applyDownLoad(['event'=>'update','module'=>$module,'version'=>$version])){
            $this->error = $service->getError();
            return false;
        }
        //在线解压到应用目录
        $zip = new \files\Zip();
        if(!$zip->unzip($saveFile, $moduleDir)){
            $file->f_delete($saveFile);
            $this->error = 'apply_unzip_failed';
            return false;
        }
        //执行升级脚本
        if(!$this->install($module, 'upgrade')){
            $file->f_delete($saveFile);
            return false;
        }
        //默认返回
        return true;
    }

    /**
    * 自动处理新增与修改
    * @param array $data 数据
    * @return array 数据集
    */
	public function write($data=[])
    {
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
    
    /**
    * 获取错误信息
    * @return string
    */
    public function getError()
    {
        return $this->error;
    }
    
    /**
    * 执行应用脚本方法
    * @param string $module 应用模块名称
    * @param string $method 方法名 install|upgrade|uninstall
    * @return bool
    */
    private function executeSql($module, $method){
        //必要参数
        if(!$module || !$method){
            return true;
        }
        //无脚本文件
        if ( !class_exists($class = '\\app\\'.$module.'\\event\\Sql') ){
            return true;
        }
        //实例化脚本类
        $Sql = new $class();
        //执行方法前方法是否已经定义 
        if( method_exists($Sql, $method) ){
            //action($module.'/Sql/install','','event');
            if(!$Sql->$method()){
                $this->error = 'apply_fail_sql%'.$module;//执行SQL语句出错
                return false;
            }
        }
        return true;
    }
}