<?php
namespace daicuo;

/**
* 导航管理
*/
class Nav {

    /**
     * 批量增加导航
     * @param array $data 写入数据（二维数组） 
     * @return null|obj 添加成功返回自增ID数据集
     */
    public static function save_all($list){
        foreach($list as $key=>$value){
            if(false === DcCheck($value, 'common/Nav')){
                return 0;
            }
            $list[$key] = self::data_post($value);
        }
        return \daicuo\Op::save_all($list);
    }
   
    /**
     * 按Id删除一个导航
     * @param string $value 字段值
     * @return int 返回操作记录数
     */
    public static function delete_id($value){
        $where = array();
        $where['op_id'] = ['eq', $value];
        return self::delete($where);
    }
    
    /**
     * 通过ID获取导航
     * @param int $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return obj|null 不为空时返回修改后的数据
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
     * 按Id修改一个导航
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
     * 新增一个新导航
     * @param array $data 写入数据（一维数组） 
     * @return int 添加成功返回自增ID
     */
    public static function save($data){
        //字段验证
        if(false === DcCheck($data, 'common/Nav')){
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
        \think\Hook::listen('nav_save_before', $params);
        //添加数据
        if( false == $params['result'] ){
            $params['result'] = \daicuo\Op::save($params['data']);
        }
        //预埋钩子
        \think\Hook::listen('nav_save_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件删除一个导航
     * @param array $where 删除条件
     * @return int 返回操作记录数
     */
    public static function delete($where){
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['result'] = 0;
        unset($where);unset($data);
        //预埋钩子
        \think\Hook::listen('nav_delete_before', $params);
        //删除数据
        if( 0 == $params['result'] ){
            $params['result'] = \daicuo\Op::delete($params['where']);
        }
        //预埋钩子
        \think\Hook::listen('nav_delete_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 修改一个导航
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @return null|obj 成功时返回obj
     */
    public static function update($where, $data){
        //字段验证
        if(false === DcCheck($data, 'common/Nav')){
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
        \think\Hook::listen('nav_update_before', $params);
        //修改数据
        if( false == $params['result'] ){
            $params['result'] = \daicuo\Op::update($params['where'], $params['data']);
        }
        //预埋钩子
        \think\Hook::listen('nav_update_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询一个导航
     * @param array $where 查询条件（一维数组）
     * @param bool $cache 是否开启缓存功能由后台统一配置
     * @return mixed null|array 不为空时返回获取器修改后的array
     */
    public static function get($where, $cache=true){
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['cache'] = $cache;
        $params['result'] = false;
        unset($where);unset($cache);
        //预埋钩子
        \think\Hook::listen('nav_get_before', $params);
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
        \think\Hook::listen('nav_get_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询多条导航
     * @param array $args 查询条件（一维数组）
     * @return obj|array 不为空时返回处理好的array
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
            'field'     => 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order,op_status',
            'sort'      => 'op_order',
            'order'     => 'desc',
            'where'     => [],
            'limit'     => 0,
            'page'      => 0,
            'paginate'  => '',
        ], $args);
        unset($args);
        //增加必要参数条件
        $params['args']['where'] = array_merge($params['args']['where'],['op_name'=>['eq','site_nav']]);
        //预埋钩子
        \think\Hook::listen('nav_all_before', $params);
        //数据库查询(由OP类接管查询缓存)
        if( false == $params['result'] ){
            $params['result'] = \daicuo\Op::all($params['args']);
        }
        //修改器转化为NAVITEM/格式化link
        if( !is_null($params['result']) ){
            $list_nav = array();
            foreach($params['result']->toArray() as $key=>$value){
                if($value['op_value']){
                    $list_nav[$key] = array_merge($value, $value['op_value']);
                }else{
                    $list_nav[$key] = $value;
                }
                $list_nav[$key]['nav_link'] = self::navLink($value['op_value']);
                unset($list_nav[$key]['op_value']);
            }
            $params['result'] = $list_nav;
            unset($list_nav);
            //是否转化为树状
            if($params['args']['tree']){
                $params['result'] = list_to_tree($params['result'], 'op_id', 'nav_parent');
                $params['result'] = tree_to_level($params['result'], 'nav_text');
            }
        }
        //预埋钩子
        \think\Hook::listen('nav_all_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 导航列表简单数组格式
     * @param array $args 查询条件（一维数组）
     * @return array;
     */
    public static function option($args){
        if(empty($args)){
            $args['tree'] = true;
            $args['cache'] = false;
        }else{
            $args = array_merge($args,['tree'=>true]);
        }
        $list = self::all($args);
        if(is_null($list)){
            return null;
        }
        $navList = array();
        $navList[0] = ' ';
        foreach($list as $key=>$value){
            $navList[$value['op_id']] = $value['nav_text'];
        }
        unset($list);
        return $navList;
    }
    
    /**
     * 转换post数据
     * @param array $data 表单数据
     * @return obj|null 不为空时返回obj
     */
    public static function data_post($data){
        $op_value = array();
        foreach($data as $key=>$value){
            if(substr($key,0,3)=='nav'){
                $op_value[$key] = $value;
                unset($data[$key]);
            }
        }
        $data['op_value'] = $op_value;
        $data['op_name'] = 'site_nav';
        $data['op_autoload'] = 'no';
        return $data;
    }
    
    /**
     * 转换导航链接
	 * @param array $op_value 导航数据
     * @return string 处理后的导航链接
     */
	private static function navLink($op_value)
    {
		if($op_value['nav_type'] == 'addon'){
			return DcUrl($op_value['nav_module'].'/'.$op_value['nav_controll'].'/'.$op_value['nav_action'], $op_value['nav_params']);
		}
		return $op_value['nav_url'];
	}
    
}