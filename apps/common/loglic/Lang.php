<?php
namespace app\common\loglic;

class Lang
{
    /**
     * 新增或修改一个自定义字段（有op_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $posts 必需;参考默认字段;默认：空
     * @return mixed 查询结果obj|null
     */
    public function write($post=[])
    {
        config('common.validate_name', 'common/Op');
        
        //字段模块标识（固定值）
        $post['op_controll'] = 'lang';
        
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
        $args['controll']  = 'lang';
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
        $args['controll']  = 'lang';
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
        $args['controll'] = 'lang';
        //查询数据
        return model('common/Config','loglic')->select($args);
    }
    
    /**
     * 插件安装时批量插入动态语言包
     * @version 1.8.10 首次引入
     * @param array $post 必需;表单数据(key=>value)成对形式;默认：空
     * @param string $module 必需;模块名称;默认：common
     * @return array $array 数据集
     */
    public function install($post=[],$module='index',$action='zh-cn')
    {
        return \daicuo\Op::write($post, $module, 'lang', $action, 0, 'no');
    }
    
    /**
     * 插件卸载时按条件删除动态语言包
     * @version 1.8.10 首次引入
     * @param string $module 必需;模块名称;默认：common
     * @return array $array 数据集
     */
    public function unInstall($module='index')
    {
        return \daicuo\Op::delete_all([
            'op_controll' => 'lang',
            'op_module'   => $module,
        ]);
    }
}