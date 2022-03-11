<?php
/*-------------------本地参数调用API接口-------------------------------*/
/**
 * 按系统分类ID获取API数据列表第N页
 * @version 1.2.0 首次引入
 * @param int $id 必需;分类ID;默认：0
 * @param int $page 必需;页码;默认：1
 * @return array 数据列表
 */
function apiTermId($id=0, $page=1){
    $term = categoryId($id);
    $term['term_api_pg'] = $page;
    $term = apiTerm($term);
    return $term['item'];
}

/**
 * 按系统分类别名获取API数据列表第N页
 * @version 1.2.0 首次引入
 * @param string $slug 必需;分类别名;默认：空
 * @param int $page 必需;页码;默认：1
 * @return array 数据列表
 */
function apiTermSlug($slug='', $page=1){
    $term = categorySlug($slug);
    $term['term_api_pg'] = $page;
    $term = apiTerm($term);
    return $term['item'];
}

/**
 * 按系统分类ID获取API数据列表最新N条(参数由后台分类设置)
 * @version 1.2.0 首次引入
 * @param int $id 必需;分类ID;默认：0
 * @param int $limit 必需;数量;默认：10
 * @return array 数据列表不带分页
 */
function apiTermIdLimit($id=0, $limit=10){
    $term = apiTerm(categoryId($id), ['limit'=>$limit]);
    return $term['item'];
}

/**
 * 按系统分类ID获取API数据列表(参数不固定)
 * @version 1.2.0 首次引入
 * @param int $id 必需;分类ID;默认：0
 * @param int $args 必需;自定义参数;默认：空
 * @return array 数据列表不带分页
 */
function apiTermIdArgs($id=0, $params=[]){
    $term = apiTerm(categoryId($id), $params);
    return $term['item'];
}

/**
 * 按系统分类信息获取API数据列表
 * @version 1.2.0 首次引入
 * @param array $term 必需;分类信息;默认：空
 * @param int $params 必需;自定义参数;默认：空
 * @return array 数据列表带分页
 */
function apiTerm($term=[], $params=[]){
    //检查分类信息
    if( !$term['term_api_tid'] ){
        return null;
    }
    //API参数
    $arg = array();
    $arg['t']     = $term['term_api_tid'];
    $arg['pg']    = DcEmpty($term['term_api_pg'], 1);
    //分页数量（需资源站支持）
    $arg['limit'] = DcEmpty($term['term_limit'],config('maccms.page_size'));
    //关键字限制
    if($term['term_api_wd']){
        $arg['wd'] = $term['term_api_wd'];
    }
    //更新时间限制
    if($term['term_api_h']){
        $arg['h'] = $term['term_api_h'];
    }
    //读取远程数据
    return apiItem( DcArrayArgs($params,$arg) );
}

/*-------------------远程API接口参数调用-------------------------------*/
/**
 * 按关键字调用最新一页 xml/json只能搜索片名
 * @version 1.0.0 首次引入
 * @param string $wd 必需;搜索关键字;默认：空
 * @param int $limit 必需;分页大小（需资源站支持）;默认：20
 * @param int $pg 必需;当前分页;;默认：1
 * @param string $api 可选;API入口地址,不带参数;默认：空
 * @return mixed array|false(读取失败时返回false)
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
 * @version 1.0.0 首次引入
 * @param int $limit 必需;分页大小（需资源站支持）;默认：20
 * @param int $pg 必需;当前分页;;默认：1
 * @param string $api 可选;API入口地址,不带参数;;默认：空
 * @return mixed array|false(读取失败时返回false)
 */
function apiNew($limit=20, $pg=1, $api=''){
    $item = apiItem(['limit'=>$limit, 'pg'=>$pg, 'order'=>'addtime', 'sort'=>'desc'], $api);
    return $item['item'];
}

/**
 * 按更新时间段调用最新数据
 * @version 1.0.0 首次引入
 * @param int $hour 必需;更新时间小时;默认：24
 * @param int $limit 必需;分页大小（需资源站支持）;默认：20
 * @param int $pg 必需;当前分页;默认：1
 * @param string $api 可选;API入口地址,不带参数;;默认：空
 * @return mixed array|false(读取失败时返回false)
 */
function apiHour($hour=24, $limit=20, $pg=1, $api=''){
    $item = apiItem(['h'=>$hour, 'limit'=>$limit, 'pg'=>$pg, 'order'=>'addtime', 'sort'=>'desc'], $api);
    //krsort($item['item']);
    return $item['item'];
}

/**
 * 按资源站分类调用最新一页
 * @version 1.0.0 首次引入
 * @param int $typeId 必需;分类ID;默认：0
 * @param int $limit 必需;分页大小（需资源站支持）;默认：20
 * @param int $pg 必需;当前分页;默认：1
 * @param string $api 可选;API入口地址,不带参数;;默认：空
 * @return mixed array|false(读取失败时返回false)
 */
function apiType($typeId=0, $limit=20, $pg=1, $api=''){
    $item = apiItem(['t'=>$typeId, 'limit'=>$limit, 'pg'=>$pg, 'order'=>'addtime', 'sort'=>'desc'], $api);
    return $item['item'];
}

/**
 * 按资源站字段直接调用远程API数据(需要API接口支持)
 * @version 1.0.0 首次引入
 * @param string field 必需;字段名称;默认：wd
 * @param string value 必需;字段值;默认：空
 * @param array params 可选;其它API参数;默认：空
 * @param string $api 可选;API入口地址,不带参数;;默认：空
 * @return mixed array|false(读取失败时返回false)
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
    //
    $item = apiItem($args, $api);
    if($returnType == 'item'){
        return $item['item'];
    }
    return $item;
}

/**
 * 调用API资源站多个数据
 * @version 1.0.0 首次引入
 * @param array $args 必需;APIURL参数;默认：空
 * @param string $apiUrl 必需;API入口地址,不带参数;默认：空
 * @param string $apiType 必需;API类型，可选json|xml|feifeicms;默认：空
 * @return mixed array|false(读取失败时返回false)
 */
function apiItem($args=[], $apiUrl='', $apiType=''){
    //分类过滤
    if($args['t'] && config('maccms.filter_tid')){
        if(in_array($args['t'], explode(',', config('maccms.filter_tid')) )){
            return null;
        }
    }
    //默认参数
    $params = [
        'ac'    => 'list',//list|detail
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
    if(empty($apiUrl)){
        $apiUrl = config('maccms.api_url');
    }
    //过滤无效参数
    $args = DcArrayEmpty($args);
    //加载API接口
    return model('maccms/Client')->item($args, $apiUrl, $apiType);
}

/**
 * 调用API资源站单个数据
 * @version 1.0.0 首次引入
 * @param int id 必需;视频id;默认：0
 * @param string $apiUrl 必需;API入口地址,不带参数;默认：空
 * @param string $apiType 必需;API类型，可选json|xml|feifeicms;默认：空
 * @return mixed array|false(读取失败时返回false)
 */
function apiDetail($id=0, $apiUrl='', $apiType=''){
    //详情ID过滤
    if($id && config('maccms.filter_ids')){
        if( in_array($id, explode(',', config('maccms.filter_ids') ) ) ){
            return null;
        }
    }
    //默认参数
    if(empty($apiUrl)){
        $apiUrl = config('maccms.api_url');
    }
    //获取数据
    return model('maccms/Client')->detail(['ids'=>$id], $apiUrl, $apiType);
}

/*-------------------MacCms常用函数-------------------------------*/

/**
 * 获取网站导航菜单列表
 * @version 1.5.1 优化
 * @version 1.4.0 优化
 * @version 1.2.0 首次引入
  * @param array $args 必需;查询条件数组格式 {
 *     @type bool $cache 可选;是否缓存;默认：true
 *     @type int $limit 可选;分页大小;默认：0
 *     @type string $sort 可选;排序字段名;默认：term_id
 *     @type string $order 可选;排序方式(asc|desc);默认：asc
 *     @type string $status 可选;显示状态（normal|hidden|public|private）;默认：空
 *     @type string $action 可选;操作名（sitebar|navbar|navs|ico）;默认：空
 *     @type array $where 可选;自定义高级查询条件;默认：空
 * }
 * @return mixed obj|null
 */
function navItem($args=[]){
    return model('common/Navs','loglic')->select($args);
}

/**
 * 获取影视分类列表
 * @version 1.4.0 优化
 * @version 1.0.0 首次引入
 * @param array $args 必需;查询条件数组格式 {
 *     @type bool $cache 可选;是否缓存;默认：true
 *     @type int $limit 可选;分页大小;默认：0
 *     @type string $sort 可选;排序字段名;默认：op_order
 *     @type string $order 可选;排序方式(asc|desc);默认：asc
 *     @type string $status 可选;显示状态（normal|hidden）;默认：空
 *     @type string $module 可选;模型名称;默认：空
 *     @type string $result 可选;返回结果(array|tree|obj);默认：array
 *     @type array $where 可选;自定义高级查询条件;默认：空
 * }
 * @return mixed obj|null
 */
function categoryItem($args=[]){
    return model('common/Term','loglic')->select( DcArrayArgs($args,[
        'cache'   => true,
        'controll'=> 'category',
        'module'  => 'maccms',
        'status'  => 'normal',
        'sort'    => 'term_order',
        'order'   => 'desc',
        'result'  => 'array',
    ]) );
}

/**
 * 按分类ID快速获取一条分类信息
 * @version 1.4.0 优化
 * @version 1.2.0 首次引入
 * @param int $value 必需;Id值;默认：空
 * @param bool $cache 必需;是否缓存;默认：true
 * @param string $status 必需;状态(normal|hidden|private|public|空);默认：normal
 * @return mixed array|null
 */
function categoryId($value=0, $cache=true, $status='normal'){
    return \daicuo\Term::get_by('term_id', $value, $cache, 'category', $status);
}

/**
 * 按分类别名快速获取一条分类信息
 * @version 1.4.0 优化
 * @version 1.2.0 首次引入
 * @param int $value 必需;分类别名值;默认：空
 * @param bool $cache 必需;是否缓存;默认：true
 * @param string $status 必需;状态();默认：true
 * @return mixed array|null
 */
function categorySlug($value='', $cache=true, $status='normal'){
    $args = [
        'module'   => 'maccms',
        'controll' => 'category',
        'cache'    => $cache,
        'status'   => $status,
        'slug'     => $value,
    ];
    return model('common/Term','loglic')->get($args);
}

/**
 * 通过分类的meta条件查询单条分类信息
 * @version 1.4.6 优化
 * @version 1.2.0 首次引入
 * @param string $metaKey 必需;key值;默认：空
 * @param string $metaValue 必需;value值;默认：空
 * @param string $operation 可选;运算规则(eq|like|neq);默认：eq
 * @return mixed obj|null
 */
function categoryMeta($metaKey='', $metaValue='', $operation='eq'){
    return model('common/Term','loglic')->get([
        'cache'      => true,
        'meta_key'   => [$operation, $metaKey],
        'meta_value' => [$operation,$metaValue],
        'with'       => 'term_meta',
        'view'       => [
            ['term', '*'],
            ['term_meta', NULL, 'term_meta.term_id=term.term_id']
        ],
    ]);
}

/**
 * 生成栏目分类站内链接
 * @version 1.4.5 优化
 * @version 1.2.0 首次引入
 * @param int $termId 系统分类ID
 * @param string $termSlug 系统分类别名
 * @param int $pageNumber 页码
 * @return string 网址链接
 */
function categoryUrl($termId=0, $termSlug='', $pageNumber=0){
    //配置规则
    $route  = config('maccms.rewrite_category');
    //伪静态链接参数
    $args = [];
    //拼音/ID
    if( preg_match('/:slug|<slug/i',$route) ){
        $args['slug'] = $termSlug;
    }else{
        $args['id'] = $termId;
    }
    //分页路径
    if($pageNumber){
        $args['pageNumber'] = $pageNumber;
    }
    //生成链接
    return DcUrl('maccms/category/index', $args);
}

/**
 * 生成播放地址站内链接
 * @version 1.4.6 优化
 * @version 1.2.0 首次引入
 * @param array $args 必需;链接参数;默认：空
 * @param array $term 可选;系统分类[term_id|term_slug];默认：空
 * @return string 网址链接
 */
function playUrl($args=[], $term=[]){
    //伪静态配置
    $route = config('maccms.rewrite_play');
    //分类参数
    if( preg_match('/:termSlug|<termSlug/i',$route) ){
        //将远程分类ID转换为本地SLUG
        if(!$term['term_slug']){
            $term['term_slug'] = typeId2termSlug($args['tid']);
        }
        //分类别名参数
        $args['termSlug'] = $term['term_slug'];
    }
    if( preg_match('/:termId|<termId/i',$route) ){
        $args['termId'] = intval($term['term_id']);
    }
    //播放链接
    unset($args['tid']);
    return DcUrl('maccms/play/index', $args);
    /*$args = [];
    $args['tid'] = $args['tid'];//远程分类ID
    $args['id'] = $args['id'];
    $args['ep'] = $args['ep'];
    $args['from'] = $args['from'];*/
}

/**
 * 生成图床站内链接
 * @version 1.4.6 优化
 * @version 1.2.0 首次引入
 * @param string $image_url 必需;图片链接;默认：空
 * @return string 网址链接
 */
function imageUrl($image_url=''){
    return DcUrlAttachment($image_url);
}

/**
 * CPS渠道转换
 * @version 1.2.0 首次引入
 * @param string $name 广告标识
 * @return string 网址链接
 */
function posterParse($name=''){
    $string = config($name);
    if(config('common.site_id')){
        return str_replace('{SITEID}', config('common.site_id'), $string);
    }
    return $string;
}

/**
 * 生成随机颜色
 * @version 1.2.0 首次引入
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
 * @version 1.2.0 首次引入
 * @param int $rand 随机数
 * @return string ico伪类
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
 * @version 1.4.6 优化
 * @version 1.2.0 首次引入
 * @param int $typeId 必需;远程分类ID;默认：0
 * @return int 本地对应ID,无对应时返回0
 */
function typeId2termId($typeId=0){
    if( $term = categoryMeta('term_api_tid',$typeId) ){
        return $term['term_id'];
    }
    return 0;
}

/**
 * 将远程APIID转为本地绑定的分类别名
 * @version 1.4.6 优化
 * @version 1.2.0 首次引入
 * @param int $typeId 必需;远程分类ID;默认：0
 * @return int 本地对应ID,无对应时返回0
 */
function typeId2termSlug($typeId=0){
    if(!$typeId){
        return uniqid();
    }
    if( $term = categoryMeta('term_api_tid',$typeId) ){
        return $term['term_slug'];
    }
    return uniqid();
}

/**
 * 过滤连续空白
 * @version 1.4.6 首次引入
 * @param string $str 待过滤的字符串
 * @return string 处理后的字符串
 */
function maccmsTrim($str=''){
    $str = str_replace("　",' ',str_replace("&nbsp;",' ',trim($str)));
    $str = preg_replace('#\s+#', ' ', $str);
    return $str;
}

/**
 * 根据对日期或时间进行格式化
 * @version 1.4.6 首次引入
 * @param string $format 必需;规定时间戳的格式;空
 * @param mixed $timestamp 可选;规定时间戳;空
 * @return string 格式化后的时间
 */
function maccmsDate($format='Y-m-d', $timestamp='')
{
    if(!is_numeric($timestamp)){
        $timestamp = strtotime($timestamp);
    }
    return date($format, $timestamp);
}

/**
 * 检测一个UTF-8字符串里是否包含繁体中文
 * @version 1.3.0 首次引入
 * @param string $str 必需;待检测字符;默认：空
 * @return bool true|false
 */
function maccmsIsBig($str='') {
    return iconv('UTF-8', 'GB2312', $str) === false ? true : false;
}

/**
 * 简体转繁体
 * @version 1.3.0 首次引入
 * @param string $str 必需;待转换字符;默认：空
 * @return string 转换后的字符
 */
function maccmss2t($str=''){
    $url = 'http://api.daicuo.cc/jianfan/?token='.maccmsToken().'&type=s2t&text='.urlencode($str);
	$json = json_decode(DcCurl('windows',10,$url), true);
	return $json['data'];
}

/**
 * 繁体转简体
 * @version 1.3.0 首次引入
 * @param string $str 必需;待转换字符;默认：空
 * @return string 转换后的字符
 */
function maccmst2s($str=''){
	$url = 'http://api.daicuo.cc/jianfan/?token='.maccmsToken().'&type=t2s&text='.urlencode($str);
	$json = json_decode(DcCurl('windows',10,$url), true);
	return $json['data'];
}

/**
 * 关键字搜索转换、将繁体字转为简体后搜索
 * @version 1.3.0 首次引入
 * @param string $str 必需;待转换字符;默认：空
 * @return string 转换后的字符
 */
function maccmsSearch($str=''){
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

/**
 * 返回MacCms默认TOKEN
 * @return string token值
 */
function maccmsToken(){
    return DcEmpty(config("common.site_token"),'af3d62d522b656bc02f0ce26010deaea');
}