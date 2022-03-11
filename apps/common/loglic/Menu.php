<?php
namespace app\common\loglic;

class Menu
{
    /**
     * 新增或修改一个后台菜单（有term_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $posts 必需;参考默认字段;默认：空
     * @return mixed 查询结果obj|null
     */
    public function write($post=[])
    {
        config('common.validate_name', 'common/Term');
        
        config('common.validate_scene', 'save');

        config('common.where_slug_unique', false);//禁用别名唯一值自动处理
        
        config('custom_fields.term_meta', '');//不需要扩展字段
        
        //固定标识
        $post['term_controll'] = 'menus';
        
        //修改
        if($post['term_id']){
            config('common.validate_scene', 'update');//验证规则修改
            return \daicuo\Term::update_id($post['term_id'], $post);
        }
        //新增
        return \daicuo\Term::save($post);
    }
    
    /**
     * 按条件删除多个后台菜单
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
            //'action'   => 'left',
        ]);
        $args['controll'] = 'menus';
        return model('common/Term','loglic')->delete($args);
    }
    
    /**
     * 按条件获取一个后台菜单
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function get($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache'    => true,
            'result'   => 'array',
        ]);
        $args['controll'] = 'menus';
        return DcTermFind($args);
    }
    
    /**
     * 按条件获取多个后台菜单
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
            //'controll' => 'menus',
            //'module'   => 'admin',
        ]);
        $args['controll'] = 'menus';
        return DcTermSelect( $args );
    }
    
    /**
     * 安装应用时批量添加后台菜单数据
     * @version 1.8.10 首次引入
     * @param array $posts 必需;二维数组格式;默认:空
     * @param string $parentName 可选;父级名称;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function install($posts=[], $parentName='')
    {
        config('common.validate_name', false);

        config('common.validate_scene', false);

        config('common.where_slug_unique', false);

        config('custom_fields.term_meta', '');

        $default = [
            'term_name'        => '菜单',
            'term_slug'        => 'admin/index/index',
            'term_info'        => 'fa-check',
            'term_module'      => 'admin',//应用名
            'term_controll'    => 'menus',//固定值
            'term_action'      => 'left',//顶部top
            'term_status'      => 'normal',//状态
            'term_type'        => '_self',//打开方式
            'term_order'       => 1,
            'term_parent'      => 0,
            'term_count'       => 0,
            'term_title'       => '',
            'term_kewords'     => '',
            'term_description' => '',
        ];
        //父级处理
        if($parentName){
            $default['term_parent'] = db('term')->where(['term_controll'=>'menus','term_name'=> $parentName])->value('term_id');
            //没找到父级不添加
            if(!$default['term_parent']){
                return false;
            }
        }
        //批量添加数据
        $result = [];
        foreach($posts as $key=>$post){
            $termId = db('term')->where([
                'term_module'   => $post['term_module'],
                'term_controll' => 'menus',
                'term_action'   => $post['term_action'],
                'term_slug'     => $post['term_slug']
            ])->value('term_id');
            if($termId < 1){
                array_push($result,\daicuo\Term::save(DcArrayArgs($post,$default),NULL));
            }
        }
        return $result;
    }
    
    /**
     * 卸载应用时按应用名删除菜单
     * @version 1.8.10 首次引入
     * @param string $module 必须;应用名;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function unInstall($module='index')
    {
        return \daicuo\Term::delete_all([
            'term_controll' => 'menus',
            'term_module'   => $module,
        ]);
    }
}