<?php
namespace daicuo;

class Route 
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
    * APP初始化路由注册
    * @return array
    */
    public static function appInt()
    {
        $route = DcCache('route_all');
        if( !$route ){
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_value,op_status';
            $args['sort']  = 'op_order';
            $args['order'] = 'asc';
            $args['where']['op_status'] = ['eq','normal'];
            $infos = self::all($args);
            if( !is_null($infos) ){
                $route = array();
                foreach($infos as $key=>$value){
                    unset($value['op_id']);
                    $value['option'] = json_decode($value['option'],true);
                    $value['pattern'] = json_decode($value['pattern'],true);
                    $route[$value['rule']] = $value;//排重
                }
                unset($infos);//销毁变量
                DcCache('route_all', $route, 0);//写入缓存
            }
        }
        foreach($route as $value){
            if(isset($value['pattern'])){
                \think\Route::rule($value['rule'], $value['address'], $value['method'], $value['option'], $value['pattern']);
            }else{
                \think\Route::rule($value['rule'], $value['address'], $value['method'], $value['option']);
            }
        }
        return $route;
    }

    /**
     * 批量增加路由规则
     * @param array $data 写入数据（二维数组） 
     * @return null|obj 添加成功返回自增ID数据集
     */
    public static function save_all($list)
    {
        foreach($list as $key=>$value){
            //手动验证数据
            if(false === DcCheck($value, 'common/Route')){
                unset($list[$key]);
                continue;
            }
            //取消自动验证
            config('common.validate_name', false);
            $list[$key] = self::data_post($value);
        }
        if(!$list){
            return null;
        }
        return \daicuo\Op::save_all($list);
    }
   
    /**
     * 按Id删除一个路由规则
     * @param string $value 字段值
     * @return int 返回操作记录数
     */
    public static function delete_id($value)
    {
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
    public static function update_id($value, $data)
    {
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
    public static function get_id($value, $cache=true)
    {
        if ( !$value ) {
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
    public static function save($data=[])
    {
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //OP验证
        config('common.validate_name', false);
        //数据库
        $result = \daicuo\Op::save($data);
        //处理结果
        if(!$result){
            self::$error = \daicuo\Op::getError();
        }else{
            DcCache('route_all', null);
        }
        //返回结果
        return $result;
    }
    
    /**
     * 按条件删除一个路由规则
     * @param array $where 删除条件
     * @return int 返回操作记录数
     */
    public static function delete($where=[])
    {
        //数据库
        $result = \daicuo\Op::delete($where);
        //清理全局缓存
        DcCache('route_all', null);
        //返回结果
        return $result;
    }
    
    /**
     * 修改一个路由规则
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @return null|obj 不为空时返回obj
     */
    public static function update($where=[], $data=[])
    {
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //OP验证
        config('common.validate_name', false);
        //数据库
        $result = \daicuo\Op::update($where, $data);
        //处理结果
        if(!$result){
            self::$error = \daicuo\Op::getError();
        }else{
            DcCache('route_all', null);
        }
        //返回结果
        return $result;
    }
    
    /**
     * 按条件查询一个路由规则
     * @param array $where 查询条件（一维数组）
     * @param bool $cache 是否开启缓存功能由后台统一配置
     * @return null|array 不为空时返回array
     */
    public static function get($where=[], $cache=true)
    {
        //数据库
        $result = \daicuo\Op::get($where, $cache);
        //获取器转化
        $result = \daicuo\Op::data_value($result);
        //返回结果
        return $result;
    }
    
    /**
     * 按条件多条查询路由规则
     * @param array $args 查询条件（一维数组）
     * @return obj|array 不为空时返回获取器后处理好的array
     */
    public static function all($args=[])
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //合并初始参数
        $args = DcArrayArgs($args,[
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
        ]);
        //模块限制条件
        $args['where'] = array_merge($args['where'],['op_name'=>['eq','site_route']]);
        //查询数据库
        $result = \daicuo\Op::all($args);
        //获取器转化
        $result = \daicuo\Op::data_value_array($result);
        //返回结果
        return $result;
    }
    
    /**
     * 转换post数据写入OP配置表
     * @param array $post 表单数据
     * @return array 二维数组
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
        $post['op_name']   = 'site_route';
		$post['op_value']  = self::data_value_set($post);
        //数据过滤
        $post = DcArrayIsset($post, ['op_name','op_value','op_module','op_controll','op_action','op_order','op_status','op_autoload']);
        //合并初始参数
        $post = DcArrayArgs($post,[
            'op_module'    => 'common',
            'op_controll'  => 'route',
            'op_action'    => 'system',
            'op_status'    => 'normal',
            'op_order'     => 0,
            'op_autoload'  => 'no',
        ]);
        return $post;
    }
    
    /**
     * 修改器:转换post数据,处理成一条符合TP路由规则格式的数据（键名为规则）
     * @param array $post 表单数据
     * @return array 二维数组
     */
    private static function data_value_set($post)
    {
        if($post['op_value']){
            return $post['op_value'];
        }
        $route = array();
		$route['rule']    = trim($post['rule']);
		$route['address'] = trim($post['address']);
		$route['method']  = trim(DcEmpty($post['method'],'*'));
		$route['option']  = trim($post['option']);//需json格式
		$route['pattern'] = trim($post['pattern']);//需json格式
		return $route;
    }
    
}