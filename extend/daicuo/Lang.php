<?php
namespace daicuo;

class Lang 
{
    /**
     * 动态配置数据库保存的语言包(当前定义的语言类型)
     * @return array 普通数组
     */
    public static function menuInit()
    {
        $item = DcCache('lang_all');
        //数据库获取所有插件动态配置的语言包
        if( !$item ){
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_name,op_value';
            $args['sort'] = 'op_id';
            $args['order'] = 'asc';
            $args['where'] = [
                'op_status'   => ['eq', 'normal'],
                'op_controll' => ['eq', 'lang'],
                'op_action'   => ['eq', config('default_lang')],
            ];
            $list = DcDbSelect('common/Op', $args);
            if( !is_null($list) ){
                $item = [];
                //key=>value形式
                foreach($list as $key=>$value){
                    $item[$value['op_name']]=$value['op_value'];
                }
                //销毁变量
                unset($list);
                //写入缓存
                if($item){
                    DcCache('lang_all', $item, 0);
                }
            }
        }
        //动态配置
        if( $item ){
            \think\lang::set($item);
        }
        return $item;
    }
}