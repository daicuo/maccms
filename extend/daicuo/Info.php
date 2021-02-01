<?php
namespace daicuo;

class Info 
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
     * 批量增加内容模型的数据
     * @param array $list 写入数据（二维数组） 
     * @return null|obj 添加成功返回自增ID数据集
     */
    public static function save_all($list)
    {
        //关联新增只有循环操作
        foreach($list as $key=>$data){
            $status[$key] = self::save($data);
        }
        //缓存标识清理
        DcCacheTag('common/Info/Item', 'clear');
        return $status;
    }
    
    /**
     * 按infoId删除一个内容模型
     * @param string $value 字段值
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete_id($value)
    {
        $value = trim( $value );
        if ( ! $value ) {
            return '0';
        }
        if($value < 1){
            return '0';
        }
        $where = array();
        $where['info_id'] = ['eq', $value];
        return self::delete($where);
    }
    
    /**
     * 按模块名删除整个内容模型
     * @param array module 模块名
     * @return array 影响数据
     */
    public static function delete_module($module)
    {
        if($module){
           return self::delete_all(['info_module'=>['eq',$module]]); 
        }
        return 0;
    }
    
    /**
     * 批量删除内容模型数据
     * @param array $where 查询条件
     * @return array 影响数据的条数
     */
    public static function delete_all($where)
    {
        $status = ['info'=>0,'info_meta'=>0,'term_map'=>0];
        $info_id = db('info')->where($where)->column('info_id');
        if($info_id){
            //预留钩子info_delete_all_before
            \think\Hook::listen('info_delete_all_before', $info_id, $status);
            $status['term_map'] = db('termMap')->where(['detail_id'=>['in',$info_id]])->delete();
            $status['info_meta'] = db('infoMeta')->where(['info_id'=>['in',$info_id]])->delete();
            $status['info'] = db('info')->where(['info_id'=>['in',$info_id]])->delete();
            //预留钩子info_delete_all_after
            \think\Hook::listen('info_delete_all_before', $info_id, $status);
            //缓存标识清理
            DcCacheTag('common/Info/Item', 'clear');
        }
        return $status;
    }
    
    /**
     * 按infoId修改一个内容模型
     * @param string $value 字段值
     * @param array $data 写入数据（一维数组） 
     * @return obj|null 不为空时返回obj
     */
    public static function update_id($value, $data)
    {
        if ( ! $data ) {
            return null;
        }
        if($value < 1){
            return null;
        }
        $where = array();
        $where['info_id'] = ['eq', $value];
        return self::update($where, $data);
    }
    
     /**
     * 通过infoId获取内容模型
     * @param string $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_id($value, $cache=true)
    {
        if ( !$value ) {
            return null;
        }
        $where = array();
        $where['info_id'] = ['eq', $value];
        $data = self::get($where, 'info_meta,term_much.term', $cache);
        if(!is_null($data)){
            return $data->toArray();
        }
        return null;
    }
    
    /**
     * 创建一个内容模型数据
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return int 返回自增ID或0
     */
    public static function save($data, $relation='info_meta,term_map')
    {
        //数据验证及处理成关联写入的格式
        if(!$data = self::data_post($data)){
            return null;
		}
        //定义钩子参数
        $params = array();
        $params['data'] = $data;
        $params['relation'] = $relation;
        $params['result'] = false;
        //释放变量
        unset($data);unset($relation);
        //添加数据前(钩子)
        \think\Hook::listen('info_save_before', $params);
        //保存表单数据到数据库
        if( false == $params['result'] ){
            $params['result'] = DcDbSave('common/Info', $params['data'], $params['relation']);
        }
        //添加数据后(钩子)
        \think\Hook::listen('info_save_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件关联删除一个内容模型
     * @param array $where 删除条件
     * @param string|array $relation 关联表 
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete($where, $relation='info_meta,term_map')
    {
        //定义钩子参数
        $params = array();
        $params['where'] = $where;
        $params['relation'] = $relation;
        $params['result'] = 0;
        //释放变量
        unset($where);unset($data);unset($relation);
        //删除数据前(钩子)
        \think\Hook::listen('info_delete_before', $params);
        //删除数据
        if( 0 == $params['result'] ){
            $params['result'] = DcDbDelete('common/Info', $params['where'], $params['relation']);
        }
        //删除数据后(钩子)
        \think\Hook::listen('info_delete_after', $params);
        //返回结果
        return implode(',', $params['result']);
    }
    
    /**
     * 按条件关联修改一个内容模型
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表
     * @return null|obj 成功时返回obj
     */
    public static function update($where, $data, $relation='info_meta,term_map')
    {
        //数据验证及处理成关联写入的格式
        if(!$data = self::data_post($data)){
            return null;
		}
        //内容模型不可修改去掉此字段
        unset($data['info_type']);
        //定义钩子参数
        $params = array();
        $params['where'] = $where;
        $params['data']  = $data;
        $params['relation'] = $relation;
        //释放变量
        unset($where);unset($data);unset($relation);
        //修改数据前(钩子)
        \think\Hook::listen('info_update_before', $params);
        //修改数据
        if( false == $params['result'] ){
            $params['result'] = DcDbUpdate('common/Info', $params['where'], $params['data'], $params['relation'], ['term_map'=>['_pk'=>'detail_id']]);
        }
        //修改数据前(钩子)
        \think\Hook::listen('info_update_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询一个内容模型
     * @param array $where 查询条件（一维数组）
     * @param string|array $relation 关联查询表
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return obj|null 成功时返回obj
     */
    public static function get($where, $with='info_meta,term_much.term', $cache=true , $view='')
    {
        //定义钩子参数
        $params = array();
        $params['result'] = false;
        $params['args'] = [
            'cache'     => $cache,
            'where'     => $where,
            'with'      => $with,
            'view'      => $view,
            'fetchSql'  => false,
        ];
        //释放变量
        unset($where);unset($relation);unset($cache);unset($view);
        //查询数据前(钩子)
        \think\Hook::listen('info_get_before', $params);
        //查询数据
        if( false == $params['result'] ){
            $params['result'] = DcDbFind('common/Info', $params['args']);
        }
        //查询数据后(钩子)
        \think\Hook::listen('info_get_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询多个内容模型
     * @param array $args 查询条件（一维数组）
     * @return obj|null 成功时返回obj
     */
    public static function all($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //定义钩子参数
        $params = array();
        $params['result'] = false;
        $params['args'] = array_merge([
            'cache'     => true,
            'field'     => '*',
            'fetchSql'  => false,
            'sort'      => 'info_id',
            'order'     => 'desc',
            'paginate'  => '',
            'where'     => '',
            'with'      => 'info_meta,term_much.term',//infoMeta,termMuch.term TP5支持小写下划线 所以统一用小写下拉
        ], $args);
        //释放变量
        unset($args);
        //查询数据前(钩子)
        \think\Hook::listen('info_all_before', $params);
        //数据库查询
        if( false == $params['result'] ){
            $params['result'] = DcDbSelect('common/Info', $params['args']);
        }
        //查询数据后(钩子)
        \think\Hook::listen('info_all_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 转换post数据
     * @param array $data 表单数据
     * @param string|array $relation 关联表 
     * @return array|null 关联写入数据格式
     */
    public static function data_post($data)
    {
        //表单验证
        $validate = [];
        $validate['data'] = $data;
        $validate['error'] = '';
        $validate['result'] = true;
        \think\Hook::listen('form_validate', $validate);
        if($validate['result'] == false){
            self::$error = $validate['error'];
            return null;
        }
        unset($validate);
        //数据整理成关联写入的格式
        $data = DcDataToMany($data, DcConfig('custom_fields.info_meta'), 'info_meta');
        $data = DcDataToMuch($data, DcConfig('custom_fields.term_map'), 'term_map');
        return $data;
    }
    
}