<?php
namespace app\common\loglic;

class Tag
{
    /**
     * 新增或修改一个标签（有term_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $posts 必需;参考默认字段;默认：空
     * @return mixed 查询结果obj|null
     */
    public function write($post=[])
    {
        $post['term_controll'] = 'tag';
        
        return model('common/Term','loglic')->write($post);
    }
    
    /**
     * 按条件删除多个标签
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type string $module 可选;应用名称;默认：空
     *     @type string $action 可选;操作名称;默认：空
     * }
     * @return mixed 查询结果obj|null
     */
    public function delete($args=[])
    {
        $args = DcArrayArgs($args,[
            'module'     => 'index',
        ]);
        $args['controll'] = 'tag';
        return model('common/Term','loglic')->delete($args);
    }
    
    /**
     * 按条件获取一个标签
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function get($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache' => true,
            'with' => 'term_meta',
        ]);
        $args['controll'] = 'tag';
        return model('common/Term','loglic')->get($args);
    }
    
    /**
     * 按条件获取多个标签
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type int    $limit 可选;分页大小;默认：0
     *     @type string $sort 可选;排序字段名;默认：op_order
     *     @type string $order 可选;排序方式(asc|desc);默认：asc
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type string $module 可选;应用名称;默认：空
     *     @type string $action 可选;应用名称;默认：空
     *     @type string $result 可选;模型名称;默认：空
     * }
     * @return mixed 查询结果obj|null
     */
    public function select($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache'    => true,
            'result'   => 'array',
            //'module'   => 'index',
        ]);
        $args['controll'] = 'tag';
        return model('common/Term','loglic')->select($args);
    }
    
    /**
     * 安装应用时批量添加标签
     * @version 1.8.10 首次引入
     * @param array $posts 必需;二维数组格式;默认:空
     * @param string $parentName 可选;父级名称;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function install($posts=[], $parentName='')
    {
        $default = [
            'term_controll'    => 'tag',//固定值
            'term_action'      => 'index',//操作名
        ];
        //批量添加数据
        $result = [];
        foreach($posts as $key=>$post){
            array_push($result, model('common/Term','loglic')->install(DcArrayArgs($post, $default),$parentName));
        }
        return $result;
    }
    
    /**
     * 卸载应用时批量标签
     * @version 1.8.10 首次引入
     * @param string $module 必须;应用名;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function unInstall($module='index')
    {
        return \daicuo\Term::delete_all([
            'term_controll' => 'tag',
            'term_module'   => $module,
        ]);
    }
}