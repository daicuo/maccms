<?php
/*-------------------本地参数调用API接口-------------------------------*/
/**
 * 按系统分类ID获取API数据列表第N页
 * @param int $id 分类ID
 * @param int $page 页码
 * @return array 数据列表
 */
function apiTermId($id, $page=1){
    $term = categoryId($id);
    $term['term_api_pg'] = $page;
    $term = apiTerm($term);
    return $term['item'];
}

/**
 * 按系统分类别名获取API数据列表第N页
 * @param string $slug 分类别名
 * @param int $page 页码
 * @return array 数据列表
 */
function apiTermSlug($slug, $page=1){
    $term = categorySlug($slug);
    $term['term_api_pg'] = $page;
    $term = apiTerm($term);
    return $term['item'];
}

/**
 * 按系统分类信息获取API数据列表
 * @param array $term 分类信息
 * @param int $page 页码
 * @return array 数据列表
 */
function apiTerm($term, $page=1){
    //检查分类信息
    if( !$term['term_api_tid'] ){
        return null;
    }
    $arg = array();
    $arg['t'] = $term['term_api_tid'];
    $arg['pg'] = DcEmpty($term['term_api_pg'],1);
    $arg['limit'] = intval(config('maccms.page_size'));//分页统一后台设置
    if($term['term_api_wd']){
        $arg['wd'] = $term['term_api_wd'];
    }
    if($term['term_api_h']){
        $arg['h'] = $term['term_api_h'];
    }
    //指定分类附加参数
    if($term['term_api_params']){
        $api_params = config('maccms.api_params');
        config('maccms.api_params', $term['term_api_params']);
    }
    $list = apiItem($arg, $term['term_api_url']);
    //还原默认附加参数
    if($api_params){
        config('maccms.api_params', $api_params);
    }
    return $list;
}

/*-------------------远程API接口参数调用-------------------------------*/

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
 * 按更新时间调用最新一页
 * @param int $hour 更新时间小时
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiNew($limit=20, $api=''){
    $list = apiItem(['pg'=>1,'limit'=>DcEmpty($limit, 20)]);
    return $item['item'];
}

/**
 * 按远程分类调用最新一页
 * @param int id 视频id
 * @param string $api API入口地址 不带参数
 * @param string $result API返回类型 可选json|xml
 * @return array|false 读取失败时返回false
 */
function apiType($typeId, $api=''){
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

/*-------------------MacCms常用函数-------------------------------*/

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
 * @param $id 分类ID值
 * @return obj|null
 */
function categoryId($id){
    return \daicuo\Term::get_id($id);
}

/**
 * 通过slug条件查询单条分类
 * @param $slug 别名值
 * @return obj|null
 */
function categorySlug($slug){
    $where = array();
    $where['term_slug'] = ['eq', $slug];
    $data = \daicuo\Term::get($where, 'term_much,term_meta');
    if(!is_null($data)){
        $data = $data->toArray();
        $data_meta = DcManyToData($data, 'term_meta');
        unset($data['term_meta']);
        return array_merge($data, $data_meta);
    }
    return null;
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
 * 生成本地分类链接
 * @param int $termId 系统分类ID
 * @param string $termSlug 系统分类别名
 * @return string 网址链接
 */
function categoryUrl($termId=0, $termSlug=''){
    if($termSlug){
        return DcUrl('maccms/category/'.DcHtml($termSlug), ['page'=>1], ''); 
    }
    return DcUrl('maccms/category/index', ['id'=>$termId,'page'=>1], '');
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
    return DcUrl('maccms/play/type', $args);
}

/**
 * 生成图床链接
 * @param string $image_url 图片链接
 * @return string 网址链接
 */
function imageUrl($image_url){
    if(config('maccms.image_url')){
        return config('maccms.image_url').urlencode($image_url);
    }
    return $image_url;
}

/**
 * 生成随机颜色
 * @param int $rand 随机数
 * @return string 颜色伪类
 */
function colorRand($rand=6){
    /*if(!in_array($rand,[0,1,2,3,4,5,6])){
        $rand = rand(0, 6);
    }*/
    $rand = rand(0, $rand);
    $text[0] = 'purple';
    $text[1] = 'secondary';
    $text[2] = 'dark';
    $text[3] = 'info';
    $text[4] = 'primary';
    $text[5] = 'danger';
    $text[6] = 'warning';
    $text[7] = 'success';
    return $text[$rand];
}

/**
 * 生成随机图标
 * @param int $rand 随机数
 * @return ico 伪类
 */
function faIcoRand($rand=19){
    $rand = rand(0, $rand);
    $ico[0] = 'fa fa-thumbs-up';
    $ico[1] = 'fa fa-hand-grab-o';
    $ico[2] = 'fa fa-hand-scissors-o';
    $ico[3] = 'fa fa-thumbs-o-up';
    $ico[4] = 'fa fa-cab';
    $ico[5] = 'fa fa-plane';
    $ico[6] = 'fa fa-subway';
    $ico[7] = 'fa fa-taxi';
    $ico[8] = 'fa fa-motorcycle';
    $ico[9] = 'fa fa-bus';
    $ico[10] = 'fa fa-space-shuttle';
    $ico[11] = 'fa fa-file-video-o';
    $ico[12] = 'fa fa-video-camera';
    $ico[13] = 'fa fa-film';
    $ico[14] = 'fa fa-flag';
    $ico[15] = 'fa fa-play-circle-o';
    $ico[16] = 'fa fa-youtube-play';
    $ico[17] = 'fa fa-play-circle';
    $ico[18] = 'fa fa-play';
    $ico[19] = 'fa fa-stop-circle';
    return $ico[$rand];
}

/**
 * 将远程APIID转为本地分类ID
 * @param int $typeId 远程分类ID
 * @return int 本地对应ID,无对应时返回0
 */
function typeId2termId($typeId){
    $terms = categoryItem();
    $types = list_search($terms,['term_api_tid'=>$typeId]);
    if( count($types)== 0 ){
        return 0;
    }
    if( count($types)> 1 ){
        $typesUrl = list_search($types, ['term_api_url'=>config('maccms.api_url')]);
        if($typesUrl){
            return $typesUrl[0]['term_id'];
        }
    }
    return $types[0]['term_id'];
}