<?php
namespace app\common\event;

/**
 * 控制器分层.钩子
 */
class Hook
{
    /**
    * 钩子注册
    * @return array
    */
    public function appBegin()
    {
        $hooks = DcCache('hook_all');
        if( !$hooks ){
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_value,op_status';
            $args['sort']  = 'op_order';
            $args['order'] = 'asc';
            $args['where']['op_status'] = ['eq','normal'];
            $hooks = \daicuo\Hook::all($args);
            if(!is_null($hooks)){
                DcCache('hook_all', $hooks, 0);
            }
        }
        //\think\Hook::add('hook_base_init','app\\home\\hook\\Common');
        foreach($hooks as $key=>$value){
            if($value['hook_overlay']=='yes'){
                \think\Hook::add($value['hook_name'], [$value['hook_path'], '_overlay'=>true]);
            }else{
                \think\Hook::add($value['hook_name'], $value['hook_path']);
            }
        }
        return $hooks;
    }

}