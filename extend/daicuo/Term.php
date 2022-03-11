<?php
namespace daicuo;

class Term
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
     * 批量增加队列
     * @param array $list 写入数据（二维数组） 
     * @return mixed 添加成功返回自增ID数据集
     */
    public static function save_all($list=[])
    {
        //关联新增只有循环操作
        foreach($list as $key=>$data){
            $status[$key] = self::save($data);
        }
        //缓存标识清理
        DcCacheTag('common/Term/Item', 'clear');
        //返回结果
        return $status;
    }
    
    /**
     * 按termId删除一个队列
     * @param int $termId ID值
     * @return bool true|false
     */
    public static function delete_id($termId='')
    {
        $value = trim($termId);
        if($termId < 1){
            return false;
        }
        if( self::childrens($termId) ){
            return false;
        }
        $where = array();
        $where['term_id'] = ['eq', $termId];
        if( self::delete($where,'term_meta,term_map') ){
            return true;
        }
        return false;
    }
    
    /**
     * 按termId删除一个队列
     * @param mixed $ids 必需;ID值,多个用逗号分隔;默认：空
     * @return array 多条删除记录
     */
    public static function delete_ids($ids='')
    {
        $result = [];
        if( is_string($ids) ){
            $ids = explode(',',$ids);
        }
        foreach($ids as $key=>$value){
            array_push($result, self::delete_id($value));
        }
        return $result;
    }
    
    /**
     * 按模块名删除整个模块的分类
     * @param string $module 模块名
     * @return array 影响条数
     */
    public static function delete_module($module='')
    {
        if($module){
           return self::delete_all(['term_module'=>['eq',$module]]); 
        }
        return ['term'=>0,'term_meta'=>0,'term_map'=>0];
    }
    
    /**
     * 批量删除分类数据
     * @param array $where 查询条件
     * @return array 影响条数
     */
    public static function delete_all($where=[])
    {
        $status = ['term'=>0,'term_meta'=>0,'term_map'=>0];
        //sqlite最大参数只能1000
        if(config('database.type') == 'sqlite'){
            $limit = 950;
        }else{
            $limit = 0;
        }
        //先获取ID值
        $term_id = db('term')->where($where)->limit($limit)->column('term_id');
        if($term_id){
                
            $status['term_map'] = db('termMap')->where(['term_id'=>['in',$term_id]])->delete();
            
            $status['term_meta'] = db('termMeta')->where(['term_id'=>['in',$term_id]])->delete();
            
            $status['term'] = db('term')->where(['term_id'=>['in',$term_id]])->delete();
            
            //缓存标识清理
            DcCacheTag('common/Term/Item', 'clear');
        }
        return $status;
    }
    
    /**
     * 按termId修改一个队列
     * @param string $value 字段值
     * @param array $data 写入数据（一维数组） 
     * @return mixed obj|null
     */
    public static function update_id($id, $data)
    {
        if($id < 1){
            return null;
        }
        $where = array();
        $where['term_id'] = ['eq', $id];
        return self::update($where, $data, 'term_meta');
    }
    
    /**
     * 通过termId获取队列信息
     * @param string $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return mixed array|null
     */
    public static function get_id($value, $cache=true)
    {
        return self::get_by('term_id', $value, $cache);
    }
    
    /**
     * 通过字段获取队列信息
     * @param string $field 字段条件 
     * @param string $value 字段值
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @param string $controll 控制器名
     * @param string $status 数据状态(normal|hidden)
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_by($field='term_id', $value='', $cache=true, $controll='', $status='')
    {
        $value = trim($value);
        if ( !$value ) {
            self::$error = lang('mustIn');
            return null;
        }
        if( !in_array($field, ['term_id','term_name','term_slug']) ){
            self::$error = lang('mustIn');
            return null;
        }
        //基本条件
        $where = [];
        $where[$field] = ['eq',$value];
        //附加条件
        if($controll){
            $where['term_controll'] = ['eq', $controll];
        }
        if($status){
            $where['term_status'] = ['eq', $status];
        }
        //获取数据
        $data = self::get([
            'cache' => $cache,
            'field' => '*',
            'where' => $where,
            'with'  => 'term_meta',
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
     * 创建一个新队列
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return mixed null|obj
     */
    public static function save($data, $relation='term_meta')
    {
        //删除主键
        unset($data['term_id']);
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //返回结果
        return DcDbSave('common/Term', $data, $relation);
    }
    
    /**
     * 按条件关联删除一个队列
     * @param array $where 必需;删除条件;默认：空
     * @param mixed $relation 可选;关联表string|array;默认：term_meta
     * @return mixed null|obj
     */
    public static function delete($where=[], $relation='term_meta,term_map')
    {
        return DcDbDelete('common/Term', $where, $relation);
    }
    
    /**
     * 修改一个队列
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表
     * @return mixed null|obj
     */
    public static function update($where, $data, $relation='term_meta')
    {
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //返回结果
        return DcDbUpdate('common/Term', $where, $data, $relation);
    }
    
    /**
     * 按条件查询一个队列
     * @param array $args 查询参数
     * @return mixed obj|null
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
            'with'      => 'term_meta',
            'view'      => '',
        ]);
        //返回结果
        return DcDbFind('common/Term', $args);
    }
    
    /**
     * 按条件查询多个队列
     * @param array $args 查询条件（一维数组）
     * @return mixed obj|null
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
            'sort'      => 'term_id',
            'order'     => 'desc',
            'paginate'  => '',
            'where'     => '',
            'with'      => '',
            'view'      => [
                //['term', 'term_id,term_name,term_slug,term_module,term_status,term_order'],
                //['term_meta', 'term_meta_id', 'term_meta.term_id=term.term_id']
            ],
        ]);
        //返回结果
        return DcDbSelect('common/Term', $args);
    }
    
    /**
     * 获取递归层级处理后的队列列表
     * @param array $args 查询条件（参数请参考手册）
     * @return mixed null|array
     */
    public static function tree($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache'      => true,
            'limit'      => 0,
            'sort'       => 'term_id',
            'order'      => 'desc',
            'controll'   => 'category',
            'result'     => 'level',
        ]);
        
        //查询时强制不分页，处理后再手动分页
        $page = $args['paginate'];
        unset($args['paginate']);
        
        //查询数据
        $terms = model('common/Term','loglic')->select($args);
        
        //是否分页显示处理后的数据
        if( $terms && $page ){
            return self::tree_to_page($terms, $page);
        }
        return $terms;
    }
    
    /**
     * 按分页格式返回递归处理后的数据
     * @param array $terms 递归处理后的数据列表
     * @param array $paginate 分页参数
     * @return array 符合TP分页数据格式的数据
     */
    public static function tree_to_page($terms=[], $paginate=[])
    {
        $page = array();
        $page['total'] = count($terms);
        $page['per_page'] = DcEmpty($paginate['list_rows'], 10);
        $page['current_page'] = DcEmpty($paginate['page'], 1);
        $page['last_page'] = ceil($page['total']/$page['per_page']);
        //计算偏移量
        $offset = ($page['current_page']-1)*$page['per_page'];
        if ( $offset >= count( $terms ) ) {
            $page['data'] = array();
        } else {
            $page['data'] = array_slice( $terms, $offset, $page['per_page'], false );
        }
        unset($terms);
        return $page;
    }
    
    /**
     * 快速生成select标签Option属性的对应关系 id=>name
     * @param array $args 查询条件（一维数组）
     * @return mixed null|array
     */
    public static function option($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache'      => false,
            'result'     => 'level',//返回树状层级结构
            'controll'   => 'category',//队列类型
            'limit'      => 0,
            'page'       => 0,
            'status'     => 'normal',
            'sort'       => 'term_parent asc,term_order',
            'order'      => 'desc',
            'isSelect'   => false,
            'fieldKey'   => 'term_id',
            'fieldValue' => 'term_name',
        ]);
        //空值过滤
        $args = DcArrayEmpty($args);
        //拼装数据
        $array = array();
        if($args['isSelect']){
            $array[0] = ' ';
        }
        foreach(model('common/Term','loglic')->select($args) as $key=>$value){
            $array[$value[$args['fieldKey']]] = $value[$args['fieldValue']];
        }
        return $array;
    }
    
    /**
     * 快速生成字段与其它字段对应关系
     * @param string $field_key 用做KEY的字段
     * @param string $field_value 用做value的字段
     * @param string $controll 分类法规则
     * @param bool $tree 是否树形
     * @param bool $level 是否将树形还原成层级
     * @return mixed null|array
     */
    public static function fields($field_key='term_id', $field_value='term_slug', $controll='category', $tree=false, $level=false)
    {
        return self::option([
            'cache' => false,
            'sort'  => 'term_order',
            'order' => 'desc',
            'tree'  => $tree,
            'level' => $level,
            'controll' => $controll,
        ]);
    }
    
    /**
     * 通过队列ID获取该队列的所有子集
     * @param int $term_id 队列ID
     * @param string $term_controll 分类法规则
     * @return mixed array|null
     */
    public static function childrens($term_id='',$term_controll='category',$term_module='',$term_action='')
    {
        $args = [];
        $args['cache']    = true;
        $args['controll'] = $term_controll;
        $args['model']    = $term_module;
        $args['action']   = $term_action;
        if( $terms = model('common/Term','loglic')->select(DcArrayEmpty($args)) ){
            return get_childs($terms, $term_id, 'term_id');
        }
        return null;
    }
    
    /**
     * 通过队列的父ID获取该队列的所有父级
     * @param int $term_pid 分类父ID
     * @param string $term_controll 分类法规则
     * @return mixed array|null
     */
    public static function parents($term_pid='', $term_controll='category',$term_module='',$term_action='')
    {
        $args = [];
        $args['cache']    = true;
        $args['controll'] = $term_controll;
        $args['model']    = $term_module;
        $args['action']   = $term_action;
        if( $terms = model('common/Term','loglic')->select(DcArrayEmpty($args)) ){
            return get_parents($terms, $term_pid, 'term_id');
        }
        return null;
    }
    
    /**
     * 获取队列层级ID对应关系
     * @param string $term_controll 分类法规则
     * @return mixed null|array
     */
    public static function hierarchy($term_controll='category')
    {
        $terms = model('common/Term','loglic')->select(['cache'=>true,'controll'=>$term_controll]);
        $children = array();
        foreach ( $terms as $key => $value ) {
            if ( $value['term_parent'] > 0 ) {
                $children[ $value['term_parent'] ][] = $value['term_id'];
            }
        }
        return $children;
    }
    
    /**
     * 获取器、整体修改返回的数据类型
     * @param obj $list 必需;数据库查询结果
     * @param string $type 必需;返回类型(array|tree|level|obj);默认：空
     * @return mixed obj|array|null
     */
    public static function result($list, $type='array')
    {
        
        //自定义字段格式化
        if(in_array($type,['array','tree','level'])){
            $list = self::meta_attr_list($list);
        }
        //树形结构
        if($type == 'tree'){
            return list_to_tree($list, 'term_id', 'term_parent');
        }
        //还原层级
        if($type == 'level'){
            return tree_to_level(list_to_tree($list, 'term_id', 'term_parent'), 'term_name');
        }
        return $list;
    }
    
    /**
     * 获取器、格式化数据列表为数组
     * @param mixed $data 二维数组或OBJ数据集(array|obj)
     * @return array 格式化后的数据
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
     * @return mixed 格式化后的数据(array|null)
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
        $data = array_merge($data, DcManyToData($data, 'term_meta'));
        
        unset($data['term_meta']);
        
        return $data;
    }
    
    /**
     * 修改器、转换post数据
     * @param array $data 表单数据
     * @return array 关联写入数据格式
     */
    public static function data_post($data)
    {
        // 表单验证
        $validate = [];
        $validate['data'] = $data;
        $validate['error'] = '';
        $validate['result'] = true;
        // 定义钩子参数
        \think\Hook::listen('form_validate', $validate);
        if($validate['result'] == false){
            self::$error = $validate['error'];
            return null;
        }
        unset($validate);
        // 数据整理成关联写入的格式
        $data = DcDataToMany($data, DcConfig('custom_fields.term_meta'), 'term_meta');
        return $data;
    } 
}