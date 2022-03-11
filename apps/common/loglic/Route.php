<?php
namespace app\common\loglic;

class Route
{
    protected $error = '';
    
    public function getError(){
        return $this->error;
    }
    
    /**
     * 按条件获取一条路由
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type string $module 可选;模型名称;默认：空
     *     @type string $controll 可选;控制器名称;默认：空
     *     @type string $action 可选;操作名称;默认：空
     *     @type int $id 可选;配置ID;默认：空
     *     @type string $name 可选;配置名称(site_rotue);默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed $mixed 查询结果（array|null）
     */
    function get($args=[]){
        $cache = DcBool($args['cache'], true);
        if(!$where = $args['where']){
            $where = DcWhereFilter($args, ['module','controll','action','status','id','name'], 'eq', 'op_');
        }
        return \daicuo\Route::get($where, $cache);
    }
    
    /**
     * 按条件获取多个路由
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $field 可选;查询字段;默认：*
     *     @type string $module 可选;模型名称;默认：空
     *     @type string $controll 可选;控制器名称;默认：空
     *     @type string $action 可选;操作名称;默认：空
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type int $limit 可选;分页大小;默认：0
     *     @type int $page 可选;当前分页;默认：0
     *     @type string $sort 可选;排序字段名;默认：op_order
     *     @type string $order 可选;排序方式(asc|desc);默认：asc
     *     @type array $where 可选;自定义高级查询条件;默认：空
     *     @type array $paginate 可选;自定义高级分页参数;默认：空
     * }
     * @return mixed $mixed 查询结果（obj|null）
     */
    public function select($args=[]){
        $defaults = array();
        //默认参数转查询条件
        $defaults['where'] = DcWhereFilter($args, ['module','controll','action','status','id','name'], 'eq', 'op_');
        //关键字参数转查询条件
        if( $args['search'] ){
            $defaults['where'] = DcArrayArgs(['op_value'=>['like','%'.$args['search'].'%']], $defaults['where']);
            unset($args['search']);
        }
        //自定义查询条件
        if( isset($args['where']) ){
            $defaults['where'] = DcArrayArgs($args['where'], $defaults['where']);
            unset($args['where']);
        }
        //自定义分页参数
        if(!$args['paginate']){
            $defaults['paginate'] = DcPageFilter($args);
        }
        return \daicuo\Route::all( DcArrayArgs($args, $defaults) );
    }
    
    /**
     * 插件安装时批量插入路由规则
     * @version 1.8.10 首次引入
     * @param array $post 必需;表单数据(key=>value)成对形式;默认：空
     * @param string $module 必需;模块名称;默认：common
     * @return array $array 数据集
     */
    public function install($posts=[], $module='index')
    {
        foreach($posts as $key=>$value){
            $posts[$key]['op_module'] = DcEmpty($posts[$key]['op_module'], $module);
        }
        return \daicuo\Route::save_all($posts);
    }
    
    /**
     * 插件卸载时按条件删除路由规则
     * @version 1.8.10 首次引入
     * @param string $module 必需;模块名称;默认：common
     * @return array $array 数据集
     */
    public function unInstall($module='index')
    {
        return \daicuo\Op::delete_all([
            'op_controll' => 'route',
            'op_module'   => $module,
        ]);
    }

}