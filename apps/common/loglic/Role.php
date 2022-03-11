<?php
namespace app\common\loglic;

class Role
{
    /**
     * 新增或修改一个角色（有op_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $post 必需;参考默认字段;默认：空
     * @param string $validateName 可选;验证规则路径;默认：空
     * @param string $validateScene 可选;验证场景;默认：空
     * @param mixed $slugUnique 可选;别名规则，禁用为false;默认：空
     * @return mixed obj|null
     */
    public function write($post=[])
    {
        config('common.validate_name', 'admin/Role');
        
        //字段模块标识（固定值）
        $post['op_controll'] = 'role';
        
        //修改
        if($post['op_id']){
            return \daicuo\Op::update_id($post['op_id'], $post);
        }
        //新增
        return \daicuo\Op::save($post);
    }
    
    /**
     * 按条件删除一个角色
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function delete($args=[])
    {
        $args['controll']  = 'role';
        return model('common/Config','loglic')->delete($args);
    }
    
    /**
     * 按条件获取一个角色
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function get($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache' => true,
        ]);
        $args['controll']  = 'role';
        return model('common/Config','loglic')->get($args);
    }
    
    /**
     * 按条件获取多个角色
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function select($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache'  => true,
            'result' => 'array',
        ]);
        $args['controll'] = 'role';
        //查询数据
        return model('common/Config','loglic')->select($args);
    }
    
    /**
     * 角色组列表选项格式
     * @version 1.8.10 首次引入
     * @param bool $cache 必需;是否缓存;默认:true
     * @return array keyValue形式的数组
     */
    public function option($cache=true)
    {
        $args = array();
        $args['cache']    = $cache;
        $args['field']    = 'op_id,op_name,op_value';
        $args['sort']     = 'op_order';
        $args['order']    = 'desc';
        $args['status']   = ['eq','normal'];
        $args['controll'] = ['eq','role'];
        $options = [];
        foreach(model('common/Config','loglic')->select($args) as $key=>$value){
            $options[$value['op_name']] = $value['op_value'];
        }
        $options['administrator'] = lang('administrator');
        return $options;
    }
    
    /**
     * 插件安装时批量插入角色
     * @version 1.8.10 首次引入
     * @param array $posts 必需;二维数组格式;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function install($posts=[])
    {
        //初始字段处理
        foreach($posts as $key=>$post){
            $posts[$key] = DcArrayArgs($post,[
                'op_action'   => 'system',
                'op_controll' => 'role',
                'op_status'   => 'normal',
                'op_order'    => 0,
                'op_autoload' => 'no',
            ]);
        }
        return \daicuo\Op::save_all($posts);
    }
    
     /**
     * 卸载应用时按应用名批量角色
     * @version 1.8.10 首次引入
     * @param string $module 必须;应用名;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function unInstall($module='index')
    {
        return \daicuo\Op::delete_all([
            'op_controll' => 'role',
            'op_module'   => $module,
        ]);
    }
}