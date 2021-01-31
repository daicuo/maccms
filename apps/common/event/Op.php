<?php
namespace app\common\event;

class Op
{

    /**
     * 加载框架默认动态配置
     */
    public function appBegin()
    {
        $this->config('common');
    }
    
    /**
     * 按模块动态配置数据库定义的字段
     * @param string $module   模块
     * @param string $controll 控制器
     * @param string $action   操作名
     * @param string $autoload 自动加载
     * @return array;
     */
    public function config($module='common', $controll=NULL, $action=NULL, $autoload='yes')
    {
        //缓存获取
        $config = DcCache('config_'.$module);
        //数据库获取
        if( !$config ){
            $where = array();
            $where['op_module'] = ['eq', $module];
            $where['op_autoload'] = ['eq', $autoload];
            if($controll){
                $where['op_controll'] = ['eq', $controll];
            }
            if($action){
                $where['op_action'] = ['eq', $action];
            }
            $args = array();
            $args['cache'] = false;
            $args['field'] = '*';
            $args['sort'] = 'op_id';
            $args['order'] = 'asc';
            $args['where'] = $where;
            //$args['fetchSql'] = true;
            $list = DcDbSelect('common/Op', $args);
            if( !is_null($list) ){
                $config = array();
                foreach($list as $key=>$value){
                    $config[$value['op_name']]=$value['op_value'];
                }
                //销毁变量
                unset($list);
                //写入缓存
                if($config){
                    DcCache('config_'.$module, $config, 0);
                }
            }
        }
        //动态配置
        if( $config ){
            if(config($module)){
                config($module, array_merge(config($module), $config) );
            }else{
                config($module, $config);
            }
        }
        return $config;
    }
    
}