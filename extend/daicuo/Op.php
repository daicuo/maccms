<?php
namespace daicuo;

class Op 
{
    // 错误信息
    protected static $error = 'error';
    
    /**
     * 获取错误信息
     * @return mixed
     */
    public static function getError()
    {
        return self::$error;
    }
    
    /**
     * 按模块动态配置数据库定义的字段
     * @param string $module 模块
     * @param string $controll 控制器
     * @param string $action 操作名
     * @param string $autoload 自动加载
     * @return array 配置数组
     */
    public static function config($module='common', $controll=NULL, $action=NULL, $autoload='yes')
    {
        //缓存获取
        $config = DcCache('config_'.$module);
        //数据库获取
        if( !$config ){
            $where = array();
            $where['op_module'] = ['eq', $module];
            $where['op_autoload'] = ['eq', $autoload];
            if($controll){
                $where['op_controll'] = ['eq', $controll];
            }
            if($action){
                $where['op_action'] = ['eq', $action];
            }
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_name,op_value';
            $args['sort'] = 'op_id';
            $args['order'] = 'asc';
            $args['where'] = $where;
            //$args['fetchSql'] = true;
            $list = DcDbSelect('common/Op', $args);
            if( !is_null($list) ){
                $config = array();
                foreach($list as $key=>$value){
                    $config[$value['op_name']]=$value['op_value'];
                }
                //销毁变量
                unset($list);
                //写入缓存
                if($config){
                    DcCache('config_'.$module, $config, 0);
                }
            }
        }
        //动态配置
        if( $config ){
            if(config($module)){
                config($module, array_merge(config($module), $config) );
            }else{
                config($module, $config);
            }
        }
        return $config;
    }
    
    /**
     * 根据ID顺序批量修改权重值
     * @param mixed $ids 必需;多个ID值用逗号分隔(array|string)；默认：空 
     * @return bool true|false
     */
    public static function sort($ids='')
    {
        if( is_string($ids)){
            if( strpos($ids,',') >= 0){
                $ids = explode(',',$ids);
            }else{
                $ids = [$ids];
            }
        }
        $list = array();
        foreach($ids as $key=>$value){
            $list[$key]['op_id'] = $value;
            $list[$key]['op_order'] = $key;
        }
        if( !dbWriteAuto('op', $list) ){
            self::$error = config('daicuo.error');
            return false;
        }
        return true;
    }

    /**
     * 批量新增配置
     * @param array $list 数据（二维数组） 
     * @return obj 添加成功返回带自增ID的数据集
     */
    public static function save_all($list)
    {
        return dbInsertAll('common/Op', $list);
    }

    /**
     * 删除单条动态配置记录值中指定的KEY
     * @param array $where 查询条件（一维数组）
     * @param string $opValueKey 数组KEY值
     * @return mixed obj|null
     */
    public function delete_value_key($where=[], $opValueKey)
    {
        if($where && $opValueKey){
            $info = self::get($where, false);
            if(is_null($info)){
                return false;
            }
            $info = $info->toArray();
            if($info['op_value'][$opValueKey]){
                unset($info['op_value'][$opValueKey]);
                if($info['op_value']){
                    return self::update_id($info['op_id'], $info);
                }else{
                    return self::delete_id($info['op_id']);
                }
            }
        }
        return false;
    }
    
    /**
     * 按id删除配置/支持逗号分隔或数组格式
     * @param mixed $value 必需;字段值（string|array）;默认：空
     * @return int 影响条数
     */
    public static function delete_id($value='')
    {
        if(!$value){
            return null;
        }
        if( is_string($value)){
            if( strpos($value,',') >= 0){
                $value = explode(',',$value);
            }else{
                $value = [$value];
            }
        }
        $where = array();
        $where['op_id'] = ['in', $value];
        return self::delete_all($where);
    }
    
    /**
     * 按模块名删除整个模块的数据
     * @param string $module 必需;应用名;默认：空
     * @return int 影响条数
     */
    public static function delete_module($module='')
    {
        $where = array();
        $where['op_module'] = ['eq', $module];
        return self::delete_all($where);
    }
    
    /**
     * 批量删除数据
     * @param array $where 查询条件
     * @return int 影响条数
     */
    public static function delete_all($where)
    {
        return dbDelete('op', $where);
    }
    
    /**
     * 更新单条动态配置记录值中指定的一个KEY值
     * @param array $where 查询条件（一维数组）
     * @param string $opValueKey 数组KEY
     * @param string $opValueData 数组Value
     * @return array|false 数据
     */
    public function update_value_key($where=[], $opValueKey='', $opValueData='')
    {
        if($where && $opValueKey  && $opValueData){
            $info = self::get($where, false);
            if(is_null($info)){
                return false;
            }
            //旧数据合并
            $info = $info->toArray();
            $info['op_value'] = DcArrayArgs([$opValueKey=>$opValueData],$info['op_value']);
            return self::update_id($info['op_id'], $info);
        }
        return false;
    }
    
    /**
     * 更新单条动态配置记录值中指定的一个KEY值
     * @param array $where 必需;查询条件（一维数组）;默认：空
     * @param array $opValueData 必需;key=>Value类型的数据;默认：空
     * @return mixed 更新结果（obj|false）
     */
    public function update_value_array($where=[], $opValueData=[])
    {
        if($where && $opValueData){
            //验证更新数据的格式
            if(!is_array($opValueData)){
                return false;
            }
            //查询旧数据
            $info = self::get($where, false);
            if(is_null($info)){
                return false;
            }
            //旧数据合并
            $info = $info->toArray();
            $info['op_value'] = DcArrayArgs($opValueData, $info['op_value']);
            return self::update_id($info['op_id'], $info);
        }
        return false;
    }
    
    /**
     * 按Id修改一个配置
     * @param int $opId 必需;ID值;默认：空
     * @param array $data 必需;表单数据（一维数组）;默认：空 
     * @return mixed 不为空时返回obj(obj|null)
     */
    public static function update_id($opId, $data)
    {
        if ( !$data ) {
            return null;
        }
        if($opId < 1){
            return null;
        }
        $where = array();
        $where['op_id'] = ['eq', $opId];
        return self::update($where, $data);
    }
    
    /**
     * 通过ID获取一个配置
     * @param int $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return mixed 不为空时返回修改后的数据(array|null)
     */
    public static function get_id($value, $cache=true)
    {
        if ( ! $value ) {
            return null;
        }
        $where = array();
        $where['op_id'] = ['eq', $value];
        return self::get($where, $cache);
    }
    
    /**
     * 创建一个新配置
     * @param array $data 写入数据（一维数组） 
     * @return int 添加成功返回自增ID,失败时为0
     */
    public static function save($data)
    {
        //删除主键
        unset($data['op_id']);
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //返回结果
        return DcDbSave('common/Op', $data);
    }
    
    /**
     * 按条件删除一条配置
     * @param array $where 删除条件
     * @return mixed obj|null
     */
    public static function delete($where)
    {
        return DcDbDelete('common/Op', $where);
    }
    
    /**
     * 按条件修改配置
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组）
     * @return mixed 不为空时返回obj(null|obj)
     */
    public static function update($where, $data)
    {
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
        }
        //返回结果
        return DcDbUpdate('common/Op', $where, $data);
    }
    
    /**
     * 按条件查询一个配置
     * @param array $where 查询条件（一维数组）
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return obj|null 不为空时返回obj
     */
    public static function get($where, $cache=true)
    {
        return DcDbFind('common/Op', [
            'cache'     => $cache,
            'where'     => $where,
            'fetchSql'  => false,
        ]);
    }

    /**
     * 按条件批量查询配置
     * @param array $args 查询条件（一维数组）
     * @return obj|null 不为空时返回obj
     */
    public static function all($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //初始参数
        $args = DcArrayArgs($args, [
            'cache'    => true,
            'field'    => 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_autoload,op_status',
            'fetchSql' => false,
            'sort'     => 'op_id',
            'order'    => 'asc',
            'where'    => [],
            'limit'    => 0,
            'page'     => 0,
            'paginate' => '',
        ]);
        //返回结果
        return DcDbSelect('common/Op', $args);
    }
    
    /**
     * 批量更新与新增动态配置（自动加载的配置用此接口写入、key唯一、order=0）
     * @param array $formData 表单数据(key=>value形林)
     * @param string $module 模块名称
     * @param string $controll 控制器名
     * @param string $action 操作名
     * @param int $order 排序值
     * @param string $autoload 自动加载
     * @return mixed 数据集(obj|null)
     */
    function write($formData, $module='common', $controll=NULL, $action=NULL, $order=0, $autoload='yes', $status='normal')
    {
        $where = array();
        if($module){
            $where['op_module'] = ['eq',$module];
        }
        if($controll){
            $where['op_controll'] = ['eq',$controll];
        }
        if($action){
            $where['op_action'] = ['eq',$action];
        }
        $where_name = array();
        foreach($formData as $key=>$value){
            $where_name[] = $key;
        }
        if($where_name){
            $where['op_name'] = ['in', $where_name];
        }
        //查询数据库是否已经添加了
        $args = array();
        $args['cache'] = false;
        $args['where'] = $where;
        //$args['fetchSql'] = true;
        $list = \daicuo\Op::all($args);
        //数据库旧数据
        $data_db = array();
        if(!is_null($list)){
            foreach($list->toArray() as $key=>$value){
                $data_db[$value['op_name']] = $value;
            }
            unset($list);
        }
        //表单数据
        $data_all = array();
        foreach($formData as $key=>$value){
            $data_op = array();
            $data_op['op_name'] = $key;
            $data_op['op_value'] = $value;
            $data_op['op_module'] = $module;
            $data_op['op_controll'] = $controll;
            $data_op['op_action'] = $action;
            $data_op['op_order'] = $order;
            $data_op['op_status'] = $status;
            if($data_db[$key]){
                //新旧数据合并
                array_push($data_all, array_merge($data_db[$key], $data_op));
            }else{
                //添新数据
                $data_op['op_autoload'] = $autoload;
                array_push($data_all, $data_op);
            }
        }
        //自动新增与更新并清空全局缓存标签（不包括主键就新增）
        return DcCacheResult(dbWriteAuto('common/Op', $data_all), 'config_'.$module);
    }
    
    /**
     * 修改器、写入数据库前数据格式化
     * @param array $array 一维数组
     * @return array 验证后的数据
     */
    public static function data_post($post)
    {
        //表单验证
        $validate = [];
        $validate['data'] = $post;
        $validate['error'] = '';
        $validate['result'] = true;
        //定义钩子参数
        \think\Hook::listen('form_validate', $validate);
        if($validate['result'] == false){
            self::$error = $validate['error'];
            return null;
        }
        unset($validate);
        //数据整理
        return $post;
    }
    
    /**
     * 获取器、格式化多个op_value字段
     * @param mixed $data 二维数组或OBJ数据集(array|obj)
     * @return array 格式化后的数据
     */
    public static function data_value_array($data)
    {
        if( is_null($data) ){
            return null;
        }
        //数据结果
        if( is_object($data) ){
            /*获取分页代码
            if( method_exists($data,'render') ){
                $pages = $data->render();
            }*/
            //转化为数组
            $data = $data->toArray();
        }
        //是否分页
        if(isset($data['total'])){
            //$data['pages'] = $pages;
            foreach($data['data'] as $key=>$value){
                $data['data'][$key] = self::data_value($value);
            }
        }else{
            foreach($data as $key=>$value){
                $data[$key] = self::data_value($value);
            } 
        }
        return $data;
    }
    
    /**
     * 获取器、格式化单个op_value字段
     * @param mixed $data 一维数组或OBJ数据集(array|obj)
     * @return array 格式化后的数据
     */
    public static function data_value($data)
    {
        if( is_null($data) ){
            return null;
        }
        if( is_object($data) ){
            $data = $data->toArray();
        }
        if( is_array($data['op_value']) ){
            $array = array_merge($data, $data['op_value']);
            unset($array['op_value']);
            return $array;
        }
        return $data;
    }
}