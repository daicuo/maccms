<?php
namespace daicuo;

class Op {

    /**
     * 批量新增配置
     * @param array $list 数据（二维数组） 
     * @return obj 添加成功返回带自增ID的数据集
     */
    public static function save_all($list){
        return dbInsertAll('common/Op', $list);
    }
    
    /**
     * 批量删除配置
     * @param array $list 数据（二维数组） 
     * @return obj 添加成功返回带自增ID的数据集
     */
    public static function delete_all($where){
        return dbDelete('common/Op', $where);
    }

    /**
     * 删除单条动态配置记录值中指定的KEY
     * @param array $where 查询条件（一维数组）
     * @param string $opValueKey 数组KEY值
     * @return array|false 数据
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
     * 按id删除一个配置
     * @param string $value 字段值
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete_id($value){
        $value = trim( $value );
        if ( ! $value ) {
            return '0';
        }
        if($value < 1){
            return '0';
        }
        $where = array();
        $where['op_id'] = ['eq', $value];
        return self::delete($where);
    }
    
    /**
     * 按模块名删除整个模块的数据
     * @param array $where 查询条件（一维数组）
     * @param string $opValueKey 数组KEY值
     * @return array|false 数据
     */
    public static function delete_module($module){
        $where = array();
        $where['op_module'] = ['eq', $module];
        return DcCacheResult(dbDelete('common/Op', $where), 'config_'.$module);
    }
    
    /**
     * 更新单条动态配置记录值中指定的KEY
     * @param array $where 查询条件（一维数组）
     * @param string $opValueKey 数组KEY值
     * @return array|false 数据
     */
    public function update_value_key($where=[], $opValueKey, $opValueData)
    {
        if($where && $opValueKey  && $opValueData){
            $info = self::get($where, false);
            if(is_null($info)){
                return false;
            }
            //旧数据合并
            $info = $info->toArray();
            $info['op_value'] = array_merge($info['op_value'], [$opValueKey=>$opValueData]);
            return self::update_id($info['op_id'], $info);
        }
        return false;
    }
    
    /**
     * 按Id修改一个配置
     * @param string $value 字段值
     * @param array $data 写入数据（一维数组） 
     * @return obj|null 不为空时返回obj
     */
    public static function update_id($value, $data){
        if ( ! $value ) {
            return null;
        }
        if($value < 1){
            return null;
        }
        $where = array();
        $where['op_id'] = ['eq', $value];
        return self::update($where, $data);
    }
    
    /**
     * 通过ID获取配置
     * @param int $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_id($value, $cache=true){
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
     * @return int 添加成功返回自增ID
     */
    public static function save($data){
        //字段验证
        if(false === DcCheck($data, 'common/Op')){
            return 0;
		}
        //钩子传参定义
        $params = array();
        $params['data'] = $data;
        $params['result'] = false;
        unset($data);
        //预埋钩子
        \think\Hook::listen('op_save_before', $params);
        //添加数据
        if( false == $params['result'] ){
            $params['result'] = DcDbSave('common/Op', $params['data']);
        }
        //预埋钩子
        \think\Hook::listen('op_save_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件删除一条配置
     * @param array $where 删除条件
     * @return int 返回操作记录
     */
    public static function delete($where){
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['result'] = 0;
        unset($where);
        //预埋钩子
        \think\Hook::listen('op_delete_before', $params);
        //删除数据
        if( 0 == $params['result'] ){
            $params['result'] = DcDbDelete('common/Op', $params['where']);
        }
        //预埋钩子
        \think\Hook::listen('op_delete_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 修改一个配置
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表
     * @return null|obj 不为空时返回obj
     */
    public static function update($where, $data){
        //字段验证
        if(false === DcCheck($data, 'common/Op')){
            return 0;
		}
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['data'] = $data;
        unset($where);unset($data);
        //预埋钩子
        \think\Hook::listen('op_update_before', $params);
        //修改数据
        if( false == $params['result'] ){
            $params['result'] = DcDbUpdate('common/Op', $params['where'], $params['data']);
        }
        //预埋钩子
        \think\Hook::listen('op_update_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询一个配置
     * @param array $where 查询条件（一维数组）
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return obj|null 不为空时返回obj
     */
    public static function get($where, $cache=true){
        //钩子传参定义
        $params = array();
        $params['result'] = false;
        $params['args'] = [
            'cache'     => $cache,
            'where'     => $where,
            'fetchSql'  => false,
        ];
        //释放变量
        unset($where);unset($cache);
        //预埋钩子
        \think\Hook::listen('op_get_before', $params);
        //查询数据
        if( false == $params['result'] ){
            $params['result'] = DcDbFind('common/Op', $params['args']);
        }
        //预埋钩子
        \think\Hook::listen('op_get_after', $params);
        //返回结果
        return $params['result'];
    }

    /**
     * 按条件查询
     * @param array $args 查询条件（一维数组）
     * @return obj|null 不为空时返回obj
     */
    public static function all($args){
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //钩子传参定义
        $params = array();
        $params['result'] = false;//返回结果
        $params['args'] = array_merge([
            'cache'    => true,
            'field'    => 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_autoload,op_status',
            'fetchSql' => false,
            'sort'     => 'op_id',
            'order'    => 'asc',
            'where'    => [],
            'limit'    => 0,
            'page'     => 0,
            'paginate' => '',
        ], $args);unset($args);
        //预埋钩子
        \think\Hook::listen('op_all_before', $params);
        //数据库查询
        if( false == $params['result'] ){
            $params['result'] = DcDbSelect('common/Op', $params['args']);
        }
        //预埋钩子
        \think\Hook::listen('op_all_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 批量更新与新增动态配置（自动加载的配置用此接口写入、key唯一、order=0）
     * @param array $formData 表单数据
     * @param string $module 模块名称
     * @param string $controll 控制器名
     * @param string $action 操作名
     * @param int $order 排序值
     * @param string $autoload 自动加载
     * @return array 数据集
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
     * 写入数据库前数据格式化
     * @param array $array 一维数组
     * @return array 数据
     */
    public static function data_post($post)
    {
        $data = array();
        $data['op_name'] = $post['op_name'];
        $data['op_value'] = $post['op_value'];
        $data['op_module'] = DcEmpty($post['op_module'], 'common');
        $data['op_controll'] = $post['op_controll'];
        $data['op_action'] = $post['op_action'];
        $data['op_order'] = DcEmpty($post['op_order'], 0);
        $data['op_autoload'] = DcEmpty($post['op_autoload'], 'yes');
        $data['op_status'] = DcEmpty($post['op_status'], 'normal');
        if($post['op_id']){
            $data['op_id'] = $post['op_id'];
        }
        return $data;
    }
}