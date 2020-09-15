<?php
namespace daicuo;

/**
* 路由
*/
class Route {

    /**
     * 批量增加路由规则
     * @param array $data 写入数据（二维数组） 
     * @return null|obj 添加成功返回自增ID数据集
     */
    public static function save_all($list){
        foreach($list as $key=>$value){
            if(false === DcCheck($value, 'common/Route')){
                return 0;
            }
            $list[$key] = self::data_post($value);
        }
        return \daicuo\Op::save_all($list);
    }
   
    /**
     * 按Id删除一个路由规则
     * @param string $value 字段值
     * @return int 返回操作记录数
     */
    public static function delete_id($value){
        $where = array();
        $where['op_id'] = ['eq', $value];
        return self::delete($where);
    }
    
    /**
     * 按Id修改一个路由规则
     * @param string $value 字段值
     * @param array $data 写入数据（一维数组） 
     * @return array|null 不为空时返回array
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
     * 通过ID获取路由规则
     * @param int $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return array|null 不为空时返回获取器后的数据
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
     * 增加一个路由规则
     * @param array $data 写入数据（一维数组） 
     * @return int 添加成功返回自增ID
     */
    public static function save($data){
        //字段验证
        if(false === DcCheck($data, 'common/Route')){
            return 0;
		}
        //格式化导航格式数据
        $data = self::data_post($data);
        //钩子传参定义
        $params = array();
        $params['data'] = $data;
        $params['result'] = false;
        unset($data);
        //预埋钩子
        \think\Hook::listen('route_save_before', $params);
        //添加数据
        if( false == $params['result'] ){
            $params['result'] = \daicuo\Op::save($params['data']);
        }
        //清理全局缓存
        DcCache('route_all', null);
        //预埋钩子
        \think\Hook::listen('route_save_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件删除一个路由规则
     * @param array $where 删除条件
     * @return int 返回操作记录数
     */
    public static function delete($where, $rule_key=''){
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['result'] = 0;
        unset($where);unset($data);
        //预埋钩子
        \think\Hook::listen('route_delete_before', $params);
        //删除数据
        if( 0 == $params['result'] ){
            $params['result'] = \daicuo\Op::delete($params['where']);
        }
        //清理全局缓存
        DcCache('route_all', null);
        //预埋钩子
        \think\Hook::listen('route_delete_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 修改一个路由规则
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @return null|obj 不为空时返回obj
     */
    public static function update($where, $data){
        //字段验证
        if(false === DcCheck($data, 'common/Route')){
            return 0;
		}
        //格式化导航格式数据
        $data = self::data_post($data);
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['data'] = $data;
        $params['result'] = false;
        unset($where);unset($data);
        //预埋钩子
        \think\Hook::listen('route_update_before', $params);
        //修改数据
        if( false == $params['result'] ){
            $params['result'] = \daicuo\Op::update($params['where'], $params['data']);
        }
        //清理全局缓存
        DcCache('route_all', null);
        //预埋钩子
        \think\Hook::listen('route_update_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询一个路由规则
     * @param array $where 查询条件（一维数组）
     * @param bool $cache 是否开启缓存功能由后台统一配置
     * @return null|array 不为空时返回array
     */
    public static function get($where, $cache=true){
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['cache'] = $cache;
        $params['result'] = false;
        unset($where);unset($cache);
        //预埋钩子
        \think\Hook::listen('route_get_before', $params);
        //数据查询(由OP类接管查询缓存)
        if( false == $params['result'] ){
            $params['result'] = \daicuo\Op::get($params['where'], $params['cache']);
        }
        //获取器
        if( !is_null($params['result']) ){
            $data = $params['result']->toArray();
            $params['result'] = array_merge($data, $data['op_value']);
            unset($params['result']['op_value']);
        }
        //预埋钩子
        \think\Hook::listen('route_get_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件多条查询路由规则
     * @param array $args 查询条件（一维数组）
     * @return obj|array 不为空时返回获取器后处理好的array
     */
    public static function all($args){
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //钩子传参定义
        $params = array();
        $params['result'] = false;
        $params['args'] = array_merge([
            'cache'     => true,
            'fetchSql'  => false,
            'tree'      => false,
            'field'     => 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order',
            'sort'      => 'op_order',
            'order'     => 'desc',
            'where'     => [],
            'limit'     => 0,
            'page'      => 0,
            'paginate'  => '',
        ], $args);
        unset($args);
        //增加必要参数条件
        $params['args']['where'] = array_merge($params['args']['where'],['op_name'=>['eq','site_route']]);
        //预埋钩子
        \think\Hook::listen('route_all_before', $params);
        //数据库查询(由OP类接管查询缓存)
        if( false == $params['result'] ){
            $params['result'] = \daicuo\Op::all($params['args']);
        }
        //获取器转化
        if( !is_null($params['result']) ){
            $datas = array();
            foreach($params['result']->toArray() as $key=>$value){
                if($value['op_value']){
                    $datas[$key] = array_merge($value, $value['op_value']);
                }else{
                    $datas[$key] = $value;
                }
                unset($datas[$key]['op_value']);
            }
            $params['result'] = $datas;
            unset($datas);
        }
        //预埋钩子
        \think\Hook::listen('route_all_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 转换post数据写入OP配置表
     * @param array $post 表单数据
     * @return array 二维数组
     */
    private static function data_post($post){
        $op = array();
		$op['op_name'] = 'site_route';
		$op['op_value'] = self::data_value_set($post);
		$op['op_module'] = DcEmpty($post['op_module'],'common');
		$op['op_controll'] = DcEmpty($post['op_controll'],'route');
		$op['op_action'] = $post['op_action'];
		$op['op_order'] = 0;
        $op['op_autoload'] = 'no';
        return $op;
    }
    
    /**
     * 修改器:转换post数据,处理成一条符合TP路由规则格式的数据（键名为规则）
     * @param array $post 表单数据
     * @return array 二维数组
     */
    private static function data_value_set($post){
        if($post['op_value']){
            return $post['op_value'];
        }
        $route = array();
		$route['rule'] = trim($post['rule']);
		$route['address'] = trim($post['address']);
		$route['method'] = trim(DcEmpty($post['method'],'*'));
		$route['option'] = trim($post['option']);//需json格式
		$route['pattern'] = trim($post['pattern']);//需json格式
		return $route;
    }
    
}