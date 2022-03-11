<?php
namespace app\common\loglic;

class Field
{
    /**
     * 新增或修改一个自定义字段（有op_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $posts 必需;参考默认字段;默认：空
     * @return mixed 查询结果obj|null
     */
    public function write($post=[])
    {
        if(!$post['op_name'] || !$post['op_module'] || !$post['op_controll']){
            return 0;
        }
        
        //取消验证规则
        config('common.validate_name', false);
        
        config('common.validate_scene', false);
        
        //字段模块标识（固定值）
        $post['op_autoload'] = 'field';
        
        //修改
        if($post['op_id']){
            return \daicuo\Op::update_id($post['op_id'], $post);
        }
        //新增
        return \daicuo\Op::save($post);
    }
    
    /**
     * 按条件删除一个自定义字段
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function delete($args=[])
    {
        $args['autoload']  = 'field';
        return model('common/Config','loglic')->delete($args);
    }
    
    /**
     * 按条件获取一个自定义字段
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
        $args['autoload']  = 'field';
        return model('common/Config','loglic')->get($args);
    }
    
    /**
     * 按条件获取多个自定义字段
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function select($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache'    => true,
            'result'   => 'array',
        ]);
        $args['autoload'] = 'field';
        return model('common/Config','loglic')->select($args);
    }
    
    /**
     * 数据获取器（格式化opValue的值）
     * @version 1.8.10 首次引入
     * @param array $opValue 必需;自定义字段的属性（JSON格式）;默认：空
     * @return array 格式化后的数据
     */
    public function dataGet($opValue=[])
    {
        $opValue['title'] = lang($opValue['op_name']);
        return array_merge($opValue,json_decode($opValue['op_value'],true));
    }
    
    /**
     * 按条件生成表单扩展字段列表（动态扩展字段列表）
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式;默认：空
     * @return mixed 查询结果obj|null
     */
    public function forms($args=[], $result='array')
    {
        $args = DcArrayArgs($args,[
            'cache'    => true,
            'result'   => 'array',
            'sort'     => 'op_order',
            'order'    => 'asc',
        ]);
        $args['autoload'] = 'field';
        //查询数据
        $list = $this->select($args);
        //只返回字段key
        if($result != 'array'){
            return array_column($list,'op_name');
        }
        //返回字段列表
        $items = [];
        foreach($list as $value){
            $key = $value['op_name'];
            $items[$key] = json_decode($value['op_value'],true);
            $items[$key]['order'] = $value['op_order'];
        }
        return $items;
    }
    
    /**
     * 插件安装时批量插入自定义字段
     * @version 1.8.10 首次引入
     * @param array $posts 必需;二维数组格式;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function install($posts=[])
    {
        //初始字段处理
        foreach($posts as $key=>$post){
            $posts[$key] = DcArrayArgs($post,[
                'op_status'   => 'normal',
                'op_order'    => 0,
                'op_autoload' => 'field',
            ]);
        }
        return \daicuo\Op::save_all($posts);
    }
    
     /**
     * 卸载应用时按应用名批量删除自定义字段
     * @version 1.8.10 首次引入
     * @param string $module 必须;应用名;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function unInstall($module='index')
    {
        return \daicuo\Op::delete_all([
            'op_autoload' => 'field',
            'op_module'   => $module,
        ]);
    }
}