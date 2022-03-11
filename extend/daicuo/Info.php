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
        //缓存标签清理
        DcCacheTag('common/Info/Item', 'clear');
        return $status;
    }
    
    /**
     * 按infoId删除一个内容模型
     * @param string $value 字段值
     * @return bool $bool true|false
     */
    public static function delete_id($value)
    {
        $value = trim( $value );
        if($value < 1){
            return false;
        }
        $where = array();
        $where['info_id'] = ['eq', $value];
        if( self::delete($where,'info_meta,term_map') ){
            return true;
        }
        return false;
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
            //删除分类法对应关系
            $status['term_map'] = db('termMap')->where(['detail_id'=>['in',$info_id]])->delete();
            //删除扩展字段
            $status['info_meta'] = db('infoMeta')->where(['info_id'=>['in',$info_id]])->delete();
            //删除基础信息
            $status['info'] = db('info')->where(['info_id'=>['in',$info_id]])->delete();
            //缓存标签清理
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
        return self::get_by('info_id', $value, $cache);
    }
    
    /**
     * 通过字段获取内容信息
     * @param string $field 字段条件 
     * @param string $value 字段值
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @param string $type 内容类型限制
     * @param string $status 数据状态(normal|hidden)
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_by($field='info_id', $value='', $cache=true, $type='', $status='')
    {
        $value = trim($value);
        if ( !$value ) {
            self::$error = lang('mustIn');
            return null;
        }
        if( !in_array($field, ['info_id','info_slug','info_name','info_title']) ){
            self::$error = lang('mustIn');
            return null;
        }
        //基本条件
        $where = [];
        $where[$field] = ['eq',$value];
        if($type){
            $where['info_type'] = ['eq', $type];
        }
        if($status){
            $where['info_status'] = ['eq',$status];
        }
        //获取数据
        $data = self::get([
            'cache' => $cache,
            'field' => '*',
            'where' => $where,
            'with'  => 'info_meta,term,user',
        ]);
        //获取器修改
        $data = self::meta_attr($data);
        //返回结果
        if(is_null($data)){
            self::$error = lang('empty');
            return null;
        }
        return $data;
    }
    
    /**
     * 创建一个内容模型数据
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return int 返回自增ID或0
     */
    public static function save($data, $relation='info_meta,term_map')
    {
        //删除主键
        unset($data['info_id']);
        //数据验证及处理成关联写入的格式
        if(!$data = self::data_post($data)){
            return null;
		}
        return DcDbSave('common/Info', $data, $relation);
    }
    
    /**
     * 按条件关联删除一个内容模型
     * @param array $where 删除条件
     * @param string|array $relation 关联表 
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete($where, $relation='info_meta,term_map')
    {
        return DcDbDelete('common/Info', $where, $relation);
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
        //unset($data['info_type']);
        //返回结果
        return DcDbUpdate('common/Info', $where, $data, $relation, false);
    }
    
    /**
     * 按条件查询一个内容模型
     * @param array $args 查询参数
     * @return obj|null 成功时返回obj
     */
    public static function get($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //初始参数
        $args = DcArrayArgs($args, [
            'cache'     => true,
            'field'     => '',
            'fetchSql'  => false,
            'where'     => '',
            'with'      => 'info_meta,term,user',//user_meta|user_meta.user
            'view'      => '',
        ]);
        //返回结果
        return DcDbFind('common/Info', $args);
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
        //初始参数
        $args = DcArrayArgs($args, [
            'cache'     => true,
            'field'     => '*',
            'fetchSql'  => false,
            'sort'      => 'info_id',
            'order'     => 'desc',
            'paginate'  => '',
            'where'     => '',
            'with'      => 'info_meta,term,user',//infoMeta,termMuch.term TP5支持小写下划线 所以统一用小写下拉
            'view'      => [],
        ]);
        //返回结果
        return DcDbSelect('common/Info', $args);
    }
    
    /**
     * 获取器、整体修改返回的数据类型
     * @param obj $list 必需;数据库查询结果
     * @param string $type 必需;返回类型(array|tree|level|obj);默认：空
     * @return mixed $mixed obj|array|null
     */
    public static function result($list, $type='array')
    {
        if($type == 'array'){
            return self::meta_attr_list($list);
        }
        return $list;
    }
    
    /**
     * 获取器、格式化数据列表为数组
     * @param mixed $data 二维数组或OBJ数据集(array|obj)
     * @return array $array 格式化后的数据
     */
    public static function meta_attr_list($data)
    {
        if( is_null($data) ){
            return null;
        }
        //数据结果
        if( is_object($data) ){
            $data = $data->toArray();
        }
        //是否分页
        if(isset($data['total'])){
            foreach($data['data'] as $key=>$value){
                $data['data'][$key] = self::meta_attr($value);
            }
        }else{
            foreach($data as $key=>$value){
                $data[$key] = self::meta_attr($value);
            }
        }
        return $data;
    }
    
    /**
     * 获取器、格式化扩展表数据
     * @param mixed $data 一维数组或OBJ数据集(array|obj)
     * @return mixed $mixed 格式化后的数据(array|null)
     */
    public static function meta_attr($data)
    {
        if( is_null($data) ){
            return null;
        }
        
        if(is_string($data)){
            return $data;
        }
        
        if( is_object($data) ){
            $data = $data->toArray();
        }
        
        //扩展字段处理
        $data = array_merge($data, DcManyToData($data, 'info_meta'));
        unset($data['info_meta']);
        
        //分类处理
        foreach($data['term'] as $key=>$value){
            //删除中间表
            unset($value["pivot"]);
            //队列类型
            $termType = DcEmpty($value['term_controll'],'category');
            //分类或标签详细
            $data[$termType][$key] = $value;
            //分类或标签ID列表
            $data[$termType.'_id'][$key] = $value['term_id'];
            //分类或标签名列表
            $data[$termType.'_name'][$key] = $value['term_name'];
            //分类或标签别名列表
            $data[$termType.'_slug'][$key] = $value['term_slug'];
        }
        unset($data['term']);
        
        //用户处理
        if( isset($data['user_meta']) ){
            $data = array_merge($data, DcManyToData($data, 'user_meta'));
            unset($data['user_meta']);
        }
        
        //过滤关联查询的原始数据
        unset($data['info_meta_id']);
        unset($data['info_meta_key']);
        unset($data['info_meta_value']);
        unset($data['term_id']);
        
        //返回结果
        return $data;
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