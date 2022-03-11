<?php
namespace app\common\loglic;

class Config
{
    /**
     * 新增或修改一个动态配置（有op_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $post 必需;参考默认字段;默认：空
     * @return mixed 查询结果obj|null
     */
    public function write($post=[])
    {
        //数据过滤
        $data = DcArrayIsset($data, ['op_name','op_value','op_module','op_controll','op_action','op_order','op_status','op_autoload']);
        //合并初始参数
        $data = DcArrayArgs($data,[
            'op_module'    => 'common',
            'op_controll'  => 'config',
            'op_action'    => 'system',
            'op_order'     => 0,
            'op_autoload'  => 'yes',
            'op_status'    => 'normal',
        ]);
        //修改
        if($post['op_id']){
            return \daicuo\Op::update_id($post['op_id'], $post);
        }
        //新增
        return \daicuo\Op::save($post);
    }
    
    /**
     * 按条件删除一条动态配置
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type string $module 可选;模型名称;默认：空
     *     @type string $controll 可选;控制器名称;默认：空
     *     @type string $action 可选;操作名称;默认：空
     *     @type int $id 可选;配置ID;默认：空
     *     @type string $name 可选;配置名称;默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed $mixed 查询结果（array|null）
     */
    public function delete($args=[])
    {
        if(!$where = $args['where']){
            $where = DcWhereFilter($args, ['module','controll','action','status','id','name','autoload'], 'eq', 'op_');
        }
        return \daicuo\Op::delete($where);
    }
    
    /**
     * 按条件获取一个动态配置
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type string $module 可选;模型名称;默认：空
     *     @type string $controll 可选;控制器名称;默认：空
     *     @type string $action 可选;操作名称;默认：空
     *     @type int $id 可选;配置ID;默认：空
     *     @type string $name 可选;配置名称;默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed $mixed 查询结果（array|null）
     */
    public function get($args=[]){
        $cache = DcBool($args['cache'],true);

        if(!$where = $args['where']){
            $where = DcWhereFilter($args, ['module','controll','action','status','id','name','autoload'], 'eq', 'op_');
        }

        return DcArrayResult(\daicuo\Op::get($where, $cache));
    }
    
    /**
     * 按条件获取多个动态配置
     * @version 1.8.0 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type string $module 可选;模型名称;默认：空
     *     @type string $controll 可选;控制器名称;默认：空
     *     @type string $action 可选;操作名称;默认：空
     *     @type string $field 可选;查询字段;默认：*
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
        if(!$args['where']){
            $defaults['where'] = DcWhereFilter($args, ['module','controll','action','status','id','name','autoload'], 'eq', 'op_');
        }
        if( $args['search'] ){
            $defaults['where'] = DcArrayArgs(['op_name|op_value'=>['like','%'.$args['search'].'%']], $defaults['where']);
            unset($args['search']);
        }
        if(!$args['paginate']){
            $defaults['paginate'] = DcPageFilter($args);
        }
        return DcArrayResult( \daicuo\Op::all( DcArrayArgs($args, $defaults) ) );
    }
    
    /**
     * 批量更新与新增动态配置、常用于需自动加载的配置
     * @version 1.8.0 首次引入
     * @param array $post 必需;表单数据(key=>value)成对形式;默认：空
     * @param string $module 必需;模块名称;默认：common
     * @param string $controll 可选;控制器名;默认：NULL
     * @param string $action 可选;操作名;默认：NULL
     * @param int $order 可选;排序值;默认：0
     * @param string $autoload 可选;自动加载;默认：yes
     * @param string $status 可选;状态;默认：normal
     * @return array $array 数据集
     */
    public function writeAll($post=[], $module='common', $controll='config', $action='system', $order=0, $autoload='yes', $status='normal'){
        return \daicuo\Op::write($post, $module, $controll, $action, $order, $autoload, $status);
    }
    
    /**
     * 按ID删除多个动态配置
     * @version 1.8.10 首次引入
     * @param array $ids 必须;ID列表;默认:空
     * @return int 影响条数
     */
    public function deleteIds($ids=[]){
        return dbDelete('common/Op',['op_id'=>['in',$ids]]);;
    }
    
    /**
     * 按ID删除多个动态配置
     * @version 1.8.10 首次引入
     * @param array $ids 必须;ID列表;默认:空
     * @return int 影响条数
     */
    public function status($ids=[],$value){
        $data = [];
        $data['op_status'] = $value;
        return dbUpdate('common/Op',['op_id'=>['in',$ids]], $data);
    }
    
    /**
     * 按ID快速获取一个动态配置
     * @version 1.8.10 首次引入
     * @param int $id 必须;ID值;默认:空
     * @param bool $cache 可选;是否缓存;默认:false
     * @return array 数组形式
     */
    public function getId($id=[], $cache=false){
        return DcArrayResult(\daicuo\Op::get_id($id, $cache));
    }
    
    /**
     * 获取模块的所有动态扩展字段列表
     * @version 1.8.10 首次引入
     * @param string $module 必需;应用名；默认:common
     * @param string $controll 可选;控制器；默认:category
     * @param string $action 可选;操作名；默认:system
     * @return array 二维数组
     */
    public function metaList($module='admin', $controll='category', $action='index')
    {
        $args = [];
        $args['module']   = DcEmpty($module,'index');
        $args['controll'] = DcEmpty($controll,'category');
        $args['action']   = str_replace('index','',$action);
        return model('common/Field','loglic')->forms( DcArrayEmpty($args) );
    }
    
    /**
     * 插件安装时批量插入动态配置
     * @version 1.8.10 首次引入
     * @param array $post 必需;表单数据(key=>value)成对形式;默认：空
     * @param string $module 必需;模块名称;默认：common
     * @return array $array 数据集
     */
    public function install($post=[], $module='index')
    {
        return \daicuo\Op::write($post, $module, 'config', 'system', 0, 'yes');
    }
    
    /**
     * 插件卸载时按条件删除动态配置
     * @version 1.8.10 首次引入
     * @param string $module 必需;模块名称;默认：common
     * @return array $array 数据集
     */
    public function unInstall($module='index')
    {
        return \daicuo\Op::delete_all([
            'op_controll' => 'config',
            'op_module'   => $module,
        ]);
    }
}