<?php
namespace daicuo;

class Apply
{
    private static $error = '';
    
    /**
    * 获取错误信息
    * @return string
    */
    public function getError()
    {
        return self::$error;
    }
    
    /**
     * 模块初始化时加载框架所有插件的初始（语言/配置/函数/钩子）及动态配置
     */
    public function moduleInit($module='')
    {
        foreach(config('common.site_applys') as $key=>$value){
            //插件名不存在时不加载
            if(!$key){
                continue;
            }
            //加载插件初始配置(当前模块会自动加载,防止二次加载)
            if($key != $module){
                self::_moduleInit($key);
            }
            //加载插件动态配置(数据库里面的配置)
            \daicuo\Op::config($key,'config');
        }
    }
    
    //模块初始化时需要加载的插件初始（语言/配置/函数/钩子）
    private function _moduleInit($module='')
    {
        //加载插件初始语言包
        \think\Lang::load(APP_PATH.$module.'/lang/'.config('default_lang').'.php');
        //加载插件初始配置
        \think\Config::load(APP_PATH.$module.'/config.php');
        //引入插件初始函数
        \think\Loader::import('common', APP_PATH.$module, EXT);
        //加载插件初始钩子并注册（建议使用钩子模块添加到数据库）
        foreach(include_once(APP_PATH.$module.'/tags.php') as $key=>$value){
            \think\Hook::add($key, $value[0], $value['_overlay']);
        }
    }
  
    //获取系统所有应用列表,包含安装/禁用状态,是否需要升级
    public function appsInfo()
    {
        //以本地模块为应用信息列表
        $applys = [];
        foreach(DcAdminApply() as $module){
            $applys[$module] = DcConfigLoad(APP_PATH.$module.'/info.php', 'apply');
            $applys[$module]['save'] = false;
            $applys[$module]['update']  = false;
        }
        //读取已安装的应用记录
        foreach(config('common.site_applys') as $apply){
            if($apply){
                $applys[$apply['module']]['save'] = true;
                $applys[$apply['module']]['disable'] = $apply['disable'];
                //需要升级
                if($apply['version'] != $applys[$apply['module']]['version']){
                    $applys[$apply['module']]['update']  = true;
                }
            }
        }
        return $applys;
    }
    
    /**
    * 以本地应用同步安装记录
    * @param string $module 应用名
    * @return obj|null
    */
    public function appsCheck()
    {
        //过滤被移动的本地应用的安装记录
        $applys = DcArrayFilter(config('common.site_applys'), DcAdminApply());
        //更新安装记录
        $where = array();
        $where['op_name']   = ['eq', 'site_applys'];
        $where['op_module'] = ['eq', 'common'];
        $result = \daicuo\Op::update($where, ['op_value'=>$applys]);
        if(!$result){
            self::$error = 'apply_fail_op';
            return false;
        }
        //删除全局缓存
        self::cacheClear();
        //返回更新结果
        return $result;
    }
  
    /**
    * 在线安装应用
    * @param string $module 应用名
    * @return bool
    */
    public function installOnline($module='')
    {
        //必要参数
        if(!$module){
            self::$error = 'mustIn';
            return false;
        }
        //应用目录
        $moduleDir = './apps/'.DcDirPath($module);
        //实例化文件类
        $file = new \files\File();
        //是否已存在相同应用
        if( $file->d_has($moduleDir) ){
            self::$error = 'apply_name_unique';
            return false;
        }
        //是否有权限创建应用目录
        if(!$file->d_create($moduleDir)){
            self::$error = 'apply_dir_access';
            return false;
        }
        
        //下载应用到临时目录
        $service = new \daicuo\Service();
        if(!$saveFile = $service->applyDownLoad(['event'=>'install','module'=>$module])){
            $file->d_delete($moduleDir);
            self::$error = $service->getError();
            return false;
        }
        
        //在线解压到应用目录
        $zip = new \files\Zip();
        if(!$zip->unzip($saveFile, $moduleDir)){
            $file->d_delete($moduleDir);
            $file->f_delete($saveFile);
            self::$error = 'apply_unzip_failed';
            return false;
        }
        
        //执行安装脚本
        if(!self::install($module, 'install')){
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
    public function upgradeOnline($module='')
    {
        //必要参数
        if(!$module){
            self::$error = 'mustIn';
            return false;
        }
        //获取待升级的插件信息
        $apply = DcConfigLoad(APP_PATH.$module.'/info.php', 'apply');
        if( !$apply['module'] ){
            self::$error = 'mustIn';
            return false;
        }
      
        //获取此应用的最新版本号，版本相同则退出更新
        $service = new \daicuo\Service();
        $upJson = $service->apiUpgrade(['module'=>$apply['module']]);
        if(!$upJson['code']){
            self::$error = 'apply_update_server_error';
            return false;
        }
        if($upJson['version'] == $apply['version']){
            self::$error = 'apply_version_isnew';
            return false;
        }
        
        //下载应用到临时目录（会根据当前版本号自动返回对应的升级包，依次升级）
        if(!$saveFile = $service->applyDownLoad(['event'=>'update','module'=>$apply['module'],'version'=>$apply['version']])){
            self::$error = $service->getError();
            return false;
        }
        
        //实例化文件类
        $file = new \files\File();
        
        //在线解压到应用目录
        $zip = new \files\Zip();
        if(!$zip->unzip($saveFile, './apps/'.$apply['module'])){
            $file->f_delete($saveFile);
            self::$error = 'apply_unzip_failed';
            return false;
        }
        
        //执行升级脚本
        if(!self::install($apply['module'], 'upgrade')){
            $file->f_delete($saveFile);
            return false;
        }
        
        //默认返回
        return true;
    }
    
    /**
    * 安装与升级插件入口
    * @param string $module 应用模块名称
    * @param string $$method 脚本方法（install|upgrade）
    * @return bool
    */
    public function install($module='', $method = 'install')
    {
        //必要参数
        if(!$module){
            self::$error = 'mustIn';
            return false;
        }
        //导入待安装的插件信息
        $apply = DcConfigLoad(APP_PATH.$module.'/info.php', 'apply');
        if( !$apply ){
            self::$error = 'mustIn';
            return false;
        }
        //读取已安装的插件列表
        $applys = config('common.site_applys');
        //数据库依赖验证
        if( $apply['datatype'] ){
            //数据库不匹配
            if( !in_array(config('database.type'), $apply['datatype']) ){
                self::$error = 'apply_fail_datatype%store/index/?searchText=database';
                return false;
            }
        }
        //版本依赖验证
        foreach($apply['rely'] as $key=>$version){
            //兼容老版本
            if( is_array($version) ){
                $version = end($version);
                $version = explode('-',$version)[0];
            }
            //2021.09.01
            if($key == 'daicuo'){
                //验证框架版本
                if( !version_compare(config('daicuo.version'), $version, '>=') ){
                    self::$error = 'apply_fail_update_frame%index/index#update%'.$version;
                    return false;
                }
            }else{
                //验证依赖插件
                if( is_null($applys[$key]) ){
                    //未安装依赖插件
                    self::$error = 'apply_fail_uninstall%store/index/?searchText='.$key.'%'.$key.$version;
                    return false;
                }else{
                    //依赖插件版本过低
                    if( !version_compare($applys[$key]['version'], $version, '>=') ){
                        self::$error = 'apply_fail_update_app%store/index/?searchText='.$key.'%'.$key.$version;
                        return false;
                    }
                }
            }
        }
        //执行安装或升级脚本
        if( !self::executeSql($module, $method) ){
            return false;
        }
        //应用信息写入数据库
        if(!$status = self::write( DcArrayArgs($apply,['disable'=>false]) )){
            self::$error = 'apply_fail_op';//更新插件列表出错
            return false;
        }
        //删除全局缓存
        self::cacheClear($module);
        //返回结果
        return $status;
    }
    
    /**
    * 卸载插件(不删除应用文件)
    * @param string $module 应用模块名称
    * @return bool
    */
    public function remove($module='')
    {
        if(!$module){
            self::$error = 'mustIn';
            return false;
        }
        //依赖插件禁止卸载
        if(config('common.apply_rely')){
            if( in_array($module, explode(',',config('common.apply_rely'))) ){
                self::$error = 'apply_fail_rely';
                return false;
            }
        }
        //执行插件自卸载脚本
        if( !self::executeSql($module, 'remove') ){
            self::$error = 'apply_fail_sql';
            return false;
        }
        //删除插件安装记录
        if( !self::deleteKey($module) ){
            return false;
        }
        //返回结果
        return true;
    }
  
    /**
    * 删除插件(一同删除应用文件)先执行自身卸载脚本后再删除安装记录与文件
    * @param string $module 应用模块名称
    * @param string $isDel 是否删除应用文件
    * @return bool
    */
    public function uninstall($module='')
    {
        if(!$module){
            self::$error = 'mustIn';
            return false;
        }
        //依赖插件禁止卸载
        if(config('common.apply_rely')){
            if( in_array($module, explode(',',config('common.apply_rely'))) ){
                self::$error = 'apply_fail_rely';
                return false;
            }
        }
        //执行插件自卸载脚本
        if( !self::executeSql($module, 'uninstall') ){
            self::$error = 'apply_fail_sql';
            return false;
        }
        //删除插件安装记录
        if( !self::deleteKey($module) ){
            return false;
        }
        //删除插件目录(包括下面的文件)
        if( !in_array($module,['admin','api','common','index']) ){
            \files\Dir::delDir('./apps/'.DcDirPath($module));
        }
        return true;
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
            self::$error = 'mustIn';
            return false;
        }
        //导入待安装的插件信息
        $apply = DcConfigLoad(APP_PATH.$module.'/info.php', 'apply');
        if( $apply ){
            //是否禁用
            if($status == 'disable'){
                $apply['disable'] = true;
            }else{
                $apply['disable'] = false;
            }
            //更新插件列表
            $result = self::write( $apply );
            if(!$result){
                self::$error = 'apply_fail_op';
                return false;
            }
            //删除全局缓存并返回结果
            self::cacheClear();
            return $result;
        }
        return false;
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
        //安装列表只记录五个字段
        $opValue = [
            'module'  => $data['module'],
            'version' => $data['version'],
            'disable' => $data['disable'],
            'name'    => $data['name'],
            'info'    => $data['info'],
        ];
        //读取旧数据
        $info = \daicuo\Op::get(['op_name' => 'site_applys', 'op_module' => 'common'], false);
        //无记录则新增
        if(is_null($info)){
            $op = array();
            $op['op_name']     = 'site_applys';
            $op['op_value']    = [$data['module']=>$opValue];
            $op['op_module']   = 'common';
            $op['op_controll'] = 'config';
            $op['op_action']   = 'applys';
            $op['op_order']    = 0;
            $op['op_autoload'] = 'yes';
            return \daicuo\Op::save($op);
        }
        //新与旧合并
        $info = $info->toArray();
        $info['op_value'] = DcArrayArgs([$data['module']=>$opValue], $info['op_value']);
        return \daicuo\Op::update_id($info['op_id'], $info);
    }
  
    /**
    * 删除应用插件安装记录
    * @param string $module 应用模块名称
    * @param string $isDel 是否删除应用文件
    * @return bool
    */
    public function deleteKey($module='')
    {
        $where = array();
        $where['op_name'] = array('eq', 'site_applys');
        $where['op_module'] = array('eq', 'common');
        $status = \daicuo\Op::delete_value_key($where, $module);
        if(!$status){
            self::$error = 'apply_fail_op';//更新插件列表出错
            return false;
        }
        //删除全局缓存
        self::cacheClear($module);
        //返回结果
        return $status;
    }
    
    /**
    * 执行应用脚本方法
    * @param string $module 应用模块名称
    * @param string $method 方法名 install|upgrade|remove|uninstall
    * @return bool
    */
    private function executeSql($module='', $method=''){
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
                self::$error = 'apply_fail_sql%'.$module;//执行SQL语句出错
                return false;
            }
        }
        return true;
    }
  
    /**
    * 删除全局缓存或模块缓存
    * @param string $module 应用模块名称
    * @return none
    */
    private function cacheClear($module='')
    {
        DcCache('route_all', null);
        DcCache('hook_all', null);
        DcCache('config_common', null);
        if($module){
            DcCache('config_'.$module, null);
        }
    }
}