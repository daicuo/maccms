<?php
namespace app\common\event;

class Route{
	
    /**
    * 路由注册
    * @return array
    */
	public function appInt()
    {
        $route = DcCache('route_all');
		if( !$route ){
            $args = array();
            $args['cache'] = false;
            $args['field'] = 'op_id,op_value,op_status';
            $args['sort']  = 'op_id';
            $args['order'] = 'desc';
            $args['where']['op_status'] = ['eq','normal'];
            $infos = \daicuo\Route::all($args);
            if( !is_null($infos) ){
                $route = array();
                foreach($infos as $key=>$value){
                    unset($value['op_id']);
                    $value['option'] = json_decode($value['option'],true);
                    $value['pattern'] = json_decode($value['pattern'],true);
                    $route[$value['rule']] = $value;//排重
                }
                unset($infos);//销毁变量
                DcCache('route_all', $route, 0);//写入缓存
            }
		}
        foreach($route as $value){
            if(isset($value['pattern'])){
                \think\Route::rule($value['rule'], $value['address'], $value['method'], $value['option'], $value['pattern']);
            }else{
                \think\Route::rule($value['rule'], $value['address'], $value['method'], $value['option']);
            }
        }
		return $route;
	}

}