<?php
namespace daicuo;

class Term {

    /**
     * 架构函数
     * @param array $config
    public function __construct($config)
    {
        //self::$config = array_merge(self::$config, $config);
    }
    */
 
     /**
     * 通过termId获取队列信息
     * @param string $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_id($value, $cache=true){
        if ( ! $value ) {
            return null;
        }
        $where = array();
        $where['term_id'] = ['eq', $value];
        $data = self::get($where, 'term_much,term_meta', $cache);
        if(!is_null($data)){
            $data = $data->toArray();
            $data_meta = DcManyToData($data, 'term_meta');
            unset($data['term_meta']);
            return array_merge($data, $data_meta);
        }
        return null;
    }
    
    /**
     * 按termId删除一个队列
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
        if( self::childrens($value) ){
            return '0';
        }
        $where = array();
        $where['term_id'] = ['eq', $value];
        return self::delete($where);
    }
    
    /**
     * 按termId修改一个队列
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
        $where['term_id'] = ['eq', $value];
        return self::update($where, $data);
    }
    
    /**
     * 创建一个新队列
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return null|obj 成功时返回obj
     */
    public static function save($data, $relation='term_much,term_meta'){
        //必要验证term/termMuch
        if(false === DcCheck($data, 'common/Term', 'save')){
            //dump(config('daicuo.error'));
            return 0;
		}
        //数据整理成关联写入的格式
        if($data['term_slug']){
            $data['term_slug'] = DcSlugUnique('term', $data['term_slug']);
        }else{
            $data['term_slug'] = '';
        }
        //$data = DcDataToOne($data, 'term_much_type,term_much_info,term_much_parent,term_much_count,term_id', 'term_much');
        //$data = DcDataToMany($data, 'tpl', 'term_meta');
        $data = DcDataToOne($data, DcConfig('common.custom_fields.term_much'), 'term_much');
        $data = DcDataToMany($data, DcConfig('common.custom_fields.term_meta'), 'term_meta');
        //钩子传参定义
        $params = array();
        $params['data'] = $data;
        $params['relation'] = $relation;
        $params['result'] = false;
        unset($data);unset($relation);
        //预埋钩子
        \think\Hook::listen('term_save_before', $params);
        //添加数据
        if( false == $params['result'] ){
            $params['result'] = DcDbSave('common/Term', $params['data'], $params['relation']);
        }
        //预埋钩子
        \think\Hook::listen('term_save_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件删除一个队列
     * @param array $where 删除条件
     * @param string|array $relation 关联表 
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete($where, $relation='term_much,term_meta'){
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['relation'] = $relation;
        $params['result'] = 0;
        unset($where);unset($data);unset($relation);
        //预埋钩子
        \think\Hook::listen('term_delete_before', $params);
        //删除数据
        if( 0 == $params['result'] ){
            $params['result'] = DcDbDelete('common/Term', $params['where'], $params['relation']);
        }
        //预埋钩子
        \think\Hook::listen('term_delete_after', $params);
        //返回结果
        return implode(',', $params['result']);
    }
    
    /**
     * 修改一个队列
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表
     * @return null|obj 成功时返回obj
     */
    public static function update($where, $data, $relation='term_much,term_meta'){
        //必要字段验证term/termMuch
        if(false === DcCheck($data, 'common/Term', 'update')){
            //dump(config('daicuo.error'));
            return null;
		}
        //类型不可修改去掉此字段
        unset($data['term_much_type']);
        //数据整理成关联写入的格式
        if($data['term_slug']){
            $data['term_slug'] = DcSlugUnique('term', $data['term_slug'], $data['term_id']);
        }else{
            $data['term_slug'] = '';
        }
        $data = DcDataToOne($data, DcConfig('common.custom_fields.term_much'), 'term_much');
        $data = DcDataToMany($data, DcConfig('common.custom_fields.term_meta'), 'term_meta');
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['data'] = $data;
        $params['relation'] = $relation;
        unset($where);unset($data);unset($relation);
        //预埋钩子
        \think\Hook::listen('term_update_before', $params);
        //修改数据
        if( false == $params['result'] ){
            $params['result'] = DcDbUpdate('common/Term', $params['where'], $params['data'], $params['relation']);
        }
        //预埋钩子
        \think\Hook::listen('term_update_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询一个队列
     * @param array $where 查询条件（一维数组）
     * @param string|array $relation 关联查询表
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return obj|null 成功时返回obj
     */
    public static function get($where, $with='term_much,term_meta', $cache=true , $view=''){
        /*$view = [
            ['term', '*'],
            ['term_much', '*', 'term_much.term_id=term.term_id'],
            ['term_meta', 'term_meta_id', 'term_meta.term_id=term.term_id']
        ];*/
        //钩子传参定义
        $params = array();
        $params['result'] = false;
        $params['args'] = [
            'cache'     => $cache,
            'where'     => $where,
            'with'      => DcWith($with),
            'view'      => $view,
            'fetchSql'  => false,
        ];
        //释放变量
        unset($where);unset($relation);unset($cache);unset($view);
        //预埋钩子
        \think\Hook::listen('term_get_before', $params);
        //查询数据
        if( false == $params['result'] ){
            $params['result'] = DcDbFind('common/Term', $params['args']);
        }
        //预埋钩子
        \think\Hook::listen('term_get_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询多个队列
     * @param array $args 查询条件（一维数组）
     * @return obj|null 成功时返回obj
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
            'field'     => '*',//term.*,termMuch.term_much_id as ids
            'fetchSql'  => false,
            'sort'      => 'term_id',
            'order'     => 'desc',
            'paginate'  => '',
            'where'     => '',
            //'with'      => ['termMuch','termMeta'],
            'view'      => [
                ['term', '*'],
                ['term_much', '*', 'term_much.term_id=term.term_id'],
                //['term_meta', 'term_meta_id', 'term_meta.term_id=term.term_id']
            ],
        ], $args);unset($args);//旧参数
        //查询分类数据前的钩子
        \think\Hook::listen('term_all_before', $params);
        //数据库查询
        if( false == $params['result'] ){
            $params['result'] = DcDbSelect('common/Term', $params['args']);
        }
        //查询数据后的钩子
        \think\Hook::listen('term_all_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件一次性获取所有队列/后续做无限递归处理
     * @param array $args 查询条件（一维数组）
     * @return mixed null|array
     */
    public static function all_pad($args){
        //默认参数
        $default = [
            //'limit'   => 100,
            //'cache'   => false,
            'type'      => 'category',
            'sort'      => 'term_id',//term_much_parent desc,
            'order'     => 'desc',
        ];
        //合并参数
        if( $args ){
            $args = array_merge($default, $args);
        }else{
            $args = $default;
        }
        //固定参数
        $args['paginate'] = false;
        $args['where']['term_much_type'] = ['eq', $args['type']];
        if($args['searchText']){
            $args['where']['term_name|term_slug'] = ['like','%'.$args['searchText'].'%'];
        }else{
            if($args['ids']){
                $args['where']['term_ids'] = ['in', $args['ids']];
            }
            if($args['module']){
                $args['where']['term_module'] = ['eq', $args['module']];
            }
            if($args['name']){
                $args['where']['term_name'] = ['like','%'.$args['name'].'%'];
            }
            if($args['slug']){
                $args['where']['term_slug'] = ['like','%'.$args['slug'].'%'];
            }
        }
        unset($args['type']);
        unset($args['ids']);
        unset($args['name']);
        unset($args['slug']);
        unset($args['module']);
        unset($args['searchText']);
        //调用数据
        $list = self::all($args);
        if(is_null($list)){
            return null;
        }
        return $list->toArray();
    }
    
    /**
     * 获取递归层级处理后的队列列表
     * @param array $args 查询条件（参数请参考手册）
     * @return mixed null|array
     */
    public static function tree($args){
        $terms = self::all_pad($args);
        if($terms){
            $terms = list_to_tree($terms, 'term_id', 'term_much_parent');
            $terms = tree_to_level($terms, 'term_name');
            //是否分页显示处理后的数据
            if( $args['paginate'] ){
                return self::tree_to_page($terms, $args['paginate']);
            }
        }
        return $terms;
    }
    
    /**
     * 按分页格式返回递归处理后的数据
     * @param array $terms 递归处理后的数据列表
     * @param array $paginate 分页参数
     * @return array 符合TP分页数据格式的数据
     */
    public static function tree_to_page($terms, $paginate){
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
     * @return mixed null|array;
     */
    public static function option($args){
        $terms = self::tree($args);
        $array = array();
        $array[0] = ' ';
        foreach($terms as $key=>$value){
            $array[$value['term_id']] = $value['term_name'];
        }
        unset($terms);
        return $array;
    }
    
    /**
     * 快速生成字段与其它字段对应关系
     * @param string $field_key 用做KEY的字段
     * @param string $field_value 用做value的字段
     * @param string $term_type 分类法规则
     * @return mixed null|array;
     */
    public static function fields($field_key='term_id', $field_value='term_slug', $term_type='category'){
        $terms = self::all_pad(['type'=>$term_type]);
        $array = array();
        foreach($terms as $key=>$value){
            $array[$fieldKey] = $value[$fieldValue];
        }
        unset($terms);
        return $array;
    }
    
    /**
     * 通过队列ID获取该队列的所有子集
     * @param int $term_id 队列ID
     * @param string $term_type 分类法规则
     * @return mixed array|null
     */
    public static function childrens($term_id, $term_type='category'){
        if( $terms = self::all_pad(['type'=>$term_type]) ){
            return get_childs($terms, $term_id);
        }
        return null;
    }
    
    /**
     * 通过队列的父ID获取该队列的所有父级
     * @param int $term_pid 分类父ID
     * @param string $term_type 分类法规则
     * @return mixed array|null
     */
    public static function parents($term_pid, $term_type='category'){
        if( $terms = self::all_pad(['type'=>$term_type]) ){
            return get_parents($terms, $term_pid);
        }
        return null;
    }
    
    /**
     * 获取队列层级ID对应关系
     * @param string $term_type 分类法规则
     * @return mixed null|array
     */
    public static function hierarchy($term_type='category'){
        $terms = self::all_pad(['type'=>$term_type]);
        $children = array();
        foreach ( $terms as $key => $value ) {
            if ( $value['term_much_parent'] > 0 ) {
                $children[ $value['term_much_parent'] ][] = $value['term_id'];
            }
        }
        return $children;
    }
    
}