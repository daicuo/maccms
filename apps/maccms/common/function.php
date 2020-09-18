<?php
//添加资源站
function apiAdd($apiUrl){
    $event = controller('maccms/Client','event');
    return $event->api($apiUrl);
}

/**
 * 调用API远程单个数据
 * @param int id 视频id
 * @param string $api API入口地址 不带参数
 * @param string $result API返回类型 可选json|xml
 * @return array|false 读取失败时返回false
 */
function apiDetail($id, $api=''){
    if(empty($api)){
        $api = config('maccms.api_url');
    }
    $event = controller('maccms/Client','event');
    return $event->detail($api, ['ids'=>$id]);
}

/**
 * 按关键字调用最新一页 xml/json只能搜索片名
 * @param string $wd 搜索关键字
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiSearch($wd='', $api=''){
    if(!$wd){
        return null;
    }
    $item = apiItem(['wd'=>$wd], $api);
    return $item['item'];
}

/**
 * 按更新时间调用最新一页
 * @param int $hour 更新时间小时
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiHour($hour=24, $api=''){
    $item = apiItem(['h'=>$hour], $api);
    //krsort($item['item']);
    return $item['item'];
}

/**
 * 按远程分类调用最新一页
 * @param int id 视频id
 * @param string $api API入口地址 不带参数
 * @param string $result API返回类型 可选json|xml
 * @return array|false 读取失败时返回false
 */
function apiCategory($typeId, $api=''){
    $item = apiItem(['t'=>$typeId], $api);
    return $item['item'];
}

/**
 * 调用API远程多个详情数据
 * @param array $args APIURL参数
 * @param string $api API入品地址 不带参数
 * @param string $result API返回类型 可选json|xml
 * @return array|false 读取失败时返回false
 */
function apiItem($args=[], $api=''){
    $params = [
        'ac'    => 'list',
        'pg'    => 1,
        'h'     => '',
        't'     => '',
        'wd'    => '',
        'limit' => 20,
    ];
    if($args){
        $args = array_merge($params, $args);
    }
    if(empty($api)){
        $api = config('maccms.api_url');
    }
    $event = controller('maccms/Client', 'event');
    return $event->item($api, $args);
}

/**
 * 获取网站导航列表
 * @param $metaKey key值
 * @param $metaValue value值
 * @return obj|null
 */
function navItem($params=[]){
    $args = array();
    $args['field'] = 'op_id,op_name,op_value,op_module,op_controll,op_action,op_order';
    $args['sort']  = 'op_order';
    $args['order'] = 'asc';
    $args['tree']  = true;
    $args['where']['op_module'] = ['eq', 'maccms'];
    //$args['cache'] = false;
    //$args['fetchSql'] = true;
    //$args['limit'] = 0;
    //$args['page'] = 0;
    if($params){
        $args = array_merge($args, $params);
    }
    $list = \daicuo\Nav::all($args);
    return $list;
}

/**
 * 获取网站所有分类列表
 * @param $metaKey key值
 * @param $metaValue value值
 * @return obj|null
 */
function categoryItem($params=[]){
    $args = array();
    $args['field'] = '*';
    $args['sort'] = 'term_order desc,';
    $args['order'] = 'term_id desc';
    $args['with'] = ['termMeta'];
    $args['where']['term_much_type'] = ['eq', 'category'];
    $args['where']['term_module'] = ['eq', 'maccms'];
    //$args['cache'] = false;
    //$args['fetchSql'] = true;
    //$args['limit'] = 0;
    //$args['page'] = 1;
    //$list = \daicuo\Term::tree($args);
    if($params){
        $args = array_merge($args, $params);
    }
    $list = \daicuo\Term::all($args);
    if(is_null($list)){
        return null;
    }
    $list = $list->toArray();
    foreach($list as $key=>$value){
        if($value['term_meta']){
            $list[$key] = DcManyToData($value, 'term_meta');
        }
    }
    return $list;
}

/**
 * 通过id条件查询单条分类
 * @param $metaKey key值
 * @param $metaValue value值
 * @return obj|null
 */
function categoryId($id){
    return \daicuo\Term::get_id($id);
}

/**
 * 通过meta条件查询单条
 * @param $metaKey key值
 * @param $metaValue value值
 * @return obj|null
 */
 function categoryMeta($metaKey, $metaValue){
    $where = array();
    //$where['term_much_type'] = ['eq','category'];
    $where['term_meta_key'] = ['eq',$metaKey];
    $where['term_meta_value'] = ['eq',$metaValue];
    $join = array();
    $join[0] = ['term','*'];
    $join[1] = ['term_much','*','term_much.term_id=term.term_id'];
    $join[2] = ['term_meta','term_meta_id','term_meta.term_id=term.term_id'];
    //return \daicuo\Term::get($where, 'termMeta', true, $join);
    $data = DcDbFind('common/Term', [
        'where'=>$where,
        'view'=>$join,
        'with'=>'termMeta',
    ]);
    if(!is_null($data)){
        $data = $data->toArray();
        $data_meta = DcManyToData($data, 'term_meta');
        unset($data['term_meta']);
        return array_merge($data, $data_meta);
    }
    return $data;
}

/**
 * 生成播放地址链接
 * @param array $args 链接参数
 * @param int $termId 系统分类ID
 * @return string 网址链接
 */
function playUrl($args, $termId=0){
    if($termId){
        $args = array_merge($args, ['tid'=>$termId]);
        return DcUrl('maccms/play/index', $args);
    }
    return DcUrl('maccms/api/play', $args);
}

/**
 * 生成随机颜色
 * @param int $rand 随机数
 * @return string 颜色伪类
 */
function textRand($rand=7){
    if(!in_array($rand,[0,1,2,3,4,5,6])){
        $rand = rand(0, 6);
    }
    $text[0] = 'text-purple';
    $text[1] = 'text-primary';
    $text[2] = 'text-success';
    $text[3] = 'text-warning';
    $text[4] = 'text-danger';
    $text[5] = 'text-dark';
    $text[6] = 'text-info';
    return $text[$rand];
}