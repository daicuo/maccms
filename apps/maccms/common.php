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
 * 按系统分类ID获取API数据列表最新N条(参数由后台分类设置)
 * @param int $id 分类ID
 * @param int $limit 数量
 * @return array 数据列表不带分页
 */
function apiTermIdLimit($id, $limit=10){
    $term = apiTerm(categoryId($id), ['limit'=>$limit]);
    return $term['item'];
}

/**
 * 按系统分类ID获取API数据列表(参数不固定)
 * @param int $id 分类ID
 * @param int $args 自定义参数
 * @return array 数据列表不带分页
 */
function apiTermIdArgs($id, $params){
    $term = apiTerm(categoryId($id), $params);
    return $term['item'];
}

/**
 * 按系统分类信息获取API数据列表
 * @param array $term 分类信息
 * @param int $params 自定义参数
 * @return array 数据列表带分页
 */
function apiTerm($term, $params){
    //检查分类信息
    if( !$term['term_api_tid'] ){
        return null;
    }
    $arg = array();
    $arg['t'] = $term['term_api_tid'];
    $arg['pg'] = DcEmpty($term['term_api_pg'], 1);
    //分页统一后台设置
    $arg['limit'] = intval(config('maccms.page_size'));
    //关键字限制
    if($term['term_api_wd']){
        $arg['wd'] = $term['term_api_wd'];
    }
    //更新时间限制
    if($term['term_api_h']){
        $arg['h'] = $term['term_api_h'];
    }
    //指定分类附加参数
    if($term['term_api_params']){
        $api_params = config('maccms.api_params');
        config('maccms.api_params', $term['term_api_params']);
    }
    //自定义参数
    if($params){
        $arg = array_merge($arg, $params);
    }
    //读取远程数据
    $list = apiItem($arg, $term['term_api_url'], $term['term_api_type']);
    //还原默认附加参数
    config('maccms.api_params', $api_params);
    return $list;
}

/*-------------------远程API接口参数调用-------------------------------*/

/**
 * 按关键字调用最新一页 xml/json只能搜索片名
 * @param string $wd 搜索关键字
 * @param int $limit 分页大小（需资源站支持）
 * @param int $pg 当前分页
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiSearch($wd='', $limit=20, $pg=1, $api=''){
    if(!$wd){
        return null;
    }
    $item = apiItem(['wd'=>$wd, 'limit'=>$limit, 'pg'=>$pg, 'order'=>'addtime', 'sort'=>'desc'], $api);
    return $item['item'];
}

/**
 * 按最新时间调用最新一页
 * @param int $limit 分页大小（需资源站支持）
 * @param int $pg 当前分页
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiNew($limit=20, $pg=1, $api=''){
    $item = apiItem(['limit'=>$limit, 'pg'=>$pg, 'order'=>'addtime', 'sort'=>'desc'], $api);
    return $item['item'];
}

/**
 * 按更新时间段调用最新数据
 * @param int $hour 更新时间小时
 * @param int $limit 分页大小（需资源站支持）
 * @param int $pg 当前分页
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiHour($hour=24, $limit=20, $pg=1, $api=''){
    $item = apiItem(['h'=>$hour, 'limit'=>$limit, 'pg'=>$pg, 'order'=>'addtime', 'sort'=>'desc'], $api);
    //krsort($item['item']);
    return $item['item'];
}

/**
 * 按远程分类调用最新一页
 * @param int $typeId 分类ID
 * @param int $limit 分页大小（需资源站支持）
 * @param int $pg 当前分页
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiType($typeId, $limit=20, $pg=1, $api=''){
    $item = apiItem(['t'=>$typeId, 'limit'=>$limit, 'pg'=>$pg, 'order'=>'addtime', 'sort'=>'desc'], $api);
    return $item['item'];
}

/**
 * 按字段直接调用远程API数据(需要API接口支持)
 * @param string field 字段名称
 * @param string value 字段值
 * @param array params 其它参数
 * @param string $api API入口地址 不带参数
 * @return array|false 读取失败时返回false
 */
function apiField($field='wd', $value='', $params=[], $api=''){
    if( !$value ){
        return null;
    }
    if( !in_array($field,['wd','actor','director','writer','area','year','language','state','name','ename','letter']) ){
        return null;
    }
    $args = array();
    $args['t'] = '';//分类
    $args['h'] = 0;//时间限制
    $args['pg'] = 1;//页码
    $args['limit'] = 10;//数量
    $args['order'] = '';//排序字段
    $args['sort'] = '';//排序方式
    $args[$field] = $value;//字段:如主演actor
    $args['return'] = 'item';//page时返回分页信息
    if($params){
        $args = array_merge($args, $params);
    }
    //返回方式
    $returnType = $args['return'];
    unset($args['return']);
    $item = apiItem($args, $api);
    if($returnType == 'item'){
        return $item['item'];
    }
    return $item;
}

/**
 * 调用API远程多个详情数据
 * @param array $args APIURL参数
 * @param string $api API入品地址 不带参数
 * @param string $apiType API类型 可选json|xml|feifeicms
 * @return array|false 读取失败时返回false
 */
function apiItem($args=[], $api='', $apiType=''){
    //分类过滤
    if($args['t'] && config('maccms.filter_tid')){
        if(in_array($args['t'], explode(',', config('maccms.filter_tid')) )){
            return null;
        }
    }
    //默认参数
    $params = [
        'ac'    => 'list',
        'pg'    => 1,
        'h'     => '',
        't'     => '',
        'wd'    => '',
        'limit' => 20,
        /*以下个性参数需要CMSAPI接口支持
        'area'       => '',
        'year'       => '',
        'language'   => '',
        'actor'      => '',
        'director'   => '',
        'writer'     => '',
        'name'       => '',
        'ename'      => '',
        'letter'     => '',
        'state'      => '',
        'order'      => '',
        'sort'       => '',
        */
    ];
    if($args){
        $args = array_merge($params, $args);
    }
    if(empty($api)){
        $api = config('maccms.api_url');
    }
    return controller('maccms/Client', 'event')->item($args, $api, $apiType);
}

/**
 * 调用API远程单个数据
 * @param int id 视频id
 * @param string $api API入口地址 不带参数
 * @param string $apiType API类型 可选json|xml|feifeicms
 * @return array|false 读取失败时返回false
 */
function apiDetail($id, $api='', $apiType=''){
    //详情ID过滤
    if($id && config('maccms.filter_ids')){
        if( in_array($id, explode(',', config('maccms.filter_ids') ) ) ){
            return null;
        }
    }
    //默认参数
    if(empty($api)){
        $api = config('maccms.api_url');
    }
    //获取数据
    return controller('maccms/Client', 'event')->detail(['ids'=>$id], $api, $apiType);
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
    $args['where']['op_status'] = ['eq', 'normal'];
    //$args['where']['op_module'] = ['eq', 'maccms'];
    $args['where']['op_controll'] = ['eq', 'header'];
    //$args['cache'] = false;
    //$args['fetchSql'] = true;
    //$args['limit'] = 0;
    //$args['page'] = 0;
    if($params){
        $args = array_merge($args, $params);
    }
    //重置树型结构为2级菜单
    if( $args['tree'] ){
        $args['tree']  = false;
        $list = \daicuo\Nav::all($args);
        if($list){
             return list_to_tree($list, 'op_id', 'nav_parent');
        }
        return $list;
    }
    //默认数据级结构
    return \daicuo\Nav::all($args);
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
    $args['sort'] = 'term.term_order desc,';
    $args['order'] = 'term.term_id desc';
    $args['with'] = ['termMeta'];
    $args['where']['term_much_type'] = ['eq', 'category'];
    $args['where']['term_module'] = ['eq', 'maccms'];
    $args['where']['term_status'] = ['eq', 'normal'];
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
 * @param $operation 运算规则
 * @return obj|null
 */
 function categoryMeta($metaKey, $metaValue, $operation='eq'){
    $where = array();
    //$where['term_much_type'] = ['eq','category'];
    $where['term_meta_key'] = [$operation, $metaKey];
    $where['term_meta_value'] = [$operation, $metaValue];
    $join = array();
    $join[0] = ['term','*'];
    $join[1] = ['term_much','*','term_much.term_id=term.term_id'];
    $join[2] = ['term_meta','term_meta_id','term_meta.term_id=term.term_id'];
    //return \daicuo\Term::get($where, 'termMeta', true, $join);
    $data = DcDbFind('common/Term', [
        'cache' => false,
        'where' => $where,
        'view'  => $join,
        'with'  => 'termMeta',
        'sort'  => 'term_order desc,term_id',
        'order' => 'desc',
        //'fetchSql' => true,
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
 * 通过远程分类ID查询绑定的本地分类信息
 * @param $id 分类ID值
 * @return obj|null
 */
function categoryTypeId($typeId){
    $terms = categoryItem();
    $types = list_search($terms,['term_api_tid'=>$typeId]);
    if( count($types)== 0 ){
        return null;
    }
    if( count($types)> 1 ){
        $typesUrl = list_search($types, ['term_api_url'=>config('maccms.api_url')]);
        if($typesUrl){
            return $typesUrl[0];
        }
    }
    return $types[0];
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
function playUrl($args, $term=[]){
    $jump = [];
    $jump['from'] = $args['from'];
    $jump['tid'] = $args['tid'];//远程分类ID
    $jump['id'] = $args['id'];
    $jump['ep'] = $args['ep'];
    //本地分类参数
    if($term['term_slug']){
        unset($jump['tid']);
        return DcUrl('maccms/play/'.$term['term_slug'], $jump, '');
    }
    if($term['term_id']){
        return DcUrl('maccms/play/index', array_merge($jump, ['tid'=>$term['term_id']]), '');
    }
    //将远程分类ID转为本地分类别名
    if($term_slug = typeId2termSlug($args['tid'])){
        unset($jump['tid']);
        return DcUrl('maccms/play/'.$term_slug, $jump, '');
    }
    //按远程分类加载播放页
    return DcUrl('maccms/play/type', $jump, '');
}

/**
 * 生成图床链接
 * @param string $image_url 图片链接
 * @return string 网址链接
 */
function imageUrl($image_url){
    if(config('maccms.upload_referer')){
        return config('maccms.upload_referer').urlencode($image_url);
    }
    return $image_url;
}

/**
 * CPS渠道转换
 * @param string $name 广告标识
 * @return string 网址链接
 */
function posterParse($name){
    $string = config($name);
    if(config('common.site_id')){
        return str_replace('{SITEID}', config('common.site_id'), $string);
    }
    return $string;
}

/**
 * 生成随机颜色
 * @param int $rand 随机数
 * @return string 颜色伪类
 */
function colorRand($rand=6){
    if(!in_array($rand,[0,1,2,3,4,5,6,7,8,9])){
        $rand = rand(0, 9);
    }
    //$rand = rand(0, $rand);
    $text[0] = 'purple';
    $text[1] = 'primary';
    $text[2] = 'danger';
    $text[3] = 'info';
    $text[4] = 'success';
    $text[5] = 'danger';
    $text[6] = 'warning';
    $text[7] = 'success';
    $text[8] = 'info';
    $text[9] = 'primary';
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
 * 将远程APIID转为本地绑定的分类ID
 * @param int $typeId 远程分类ID
 * @return int 本地对应ID,无对应时返回0
 */
function typeId2termId($typeId){
    if( $term = categoryTypeId($typeId) ){
        return $term['term_id'];
    }
    return 0;
}

/**
 * 将远程APIID转为本地绑定的分类别名
 * @param int $typeId 远程分类ID
 * @return int 本地对应ID,无对应时返回0
 */
function typeId2termSlug($typeId){
    if( $term = categoryTypeId($typeId) ){
        return $term['term_slug'];
    }
    return '';
}

/** 
 * 将对象转换为多维数组 
 * 
 **/  
function objectToArray($d) {  
    if (is_object($d)) {  
        // Gets the properties of the given object  
        // with get_object_vars function  
        $d = get_object_vars($d);  
    }  
  
    if (is_array($d)) {  
        /* 
        * Return array converted to object 
        * Using __FUNCTION__ (Magic constant) 
        * for recursive call 
        */  
        return array_map(__FUNCTION__, $d);  
    }  
    else {  
        // Return array  
        return $d;  
    }  
}  
   
/** 
 * 将多维数组转换为对象 
 * 
 **/  
function arrayToObject($d) {  
    if (is_array($d)) {  
        return (object) array_map(__FUNCTION__, $d);
    } else {  
        return $d;
    }  
}

/**
 * 检测一个UTF-8字符串里是否包含繁体中文
 * @param string $str
 * @return bool
 */
function maccmsIsBig($str) {
    return iconv('UTF-8', 'GB2312', $str) === false ? true : false;
}

//简转繁
function maccmss2t($str){
    $url = 'http://api.daicuo.cc/jianfan/?token='.maccmsToken().'&type=s2t&text='.urlencode($str);
	$json = json_decode(DcCurl('windows',10,$url), true);
	return $json['data'];
}

//繁转简
function maccmst2s($str){
	$url = 'http://api.daicuo.cc/jianfan/?token='.maccmsToken().'&type=t2s&text='.urlencode($str);
	$json = json_decode(DcCurl('windows',10,$url), true);
	return $json['data'];
}

//关键字搜索转换、将繁体字转为简体后搜索
function maccmsSearch($str){
    if( config('maccms.api_search') == 't2s' ){
        //存在繁体字就转为简体
        if(maccmsIsBig($str)){
            if( $strt2s = maccmst2s($str) ){
                return $strt2s;
            }
        }
    }else if( config('maccms.api_search') == 's2t' ){
        //简体转繁体
        if( $strs2t = maccmss2t($str) ){
            return $strs2t;
        }
    }
    
    // 原样返回
	return $str;
}

//返回MacCms默认TOKEN
function maccmsToken(){
    return DcEmpty(config("common.site_token"),'af3d62d522b656bc02f0ce26010deaea');
}