<?php
use think\Cache;
use think\Config;
use think\Cookie;
use think\Db;
use think\Debug;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\Lang;
use think\Loader;
use think\Log;
use think\Model;
use think\Request;
use think\Response;
use think\Session;
use think\Url;
use think\View;
//关闭错误报告
error_reporting(E_ERROR);
//报告 runtime 错误
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
/**************************************************分类组件***************************************************/
/**
 * 获取导航列表简单数组格式
 * @param array $args 查询条件（一维数组）
 * @return mixed null|array;
 */
function DcCategoryOption($args){
    if($args){
        $args = array_merge(['cache'=>false], $args);
    }else{
        $args['cache'] = false;
    }
    return \daicuo\Term::option($args);
}
/**************************************************导航组件***************************************************/
/**
 * 获取导航列表
 * @param array $args 查询条件（一维数组）
 * @return mixed null|array;
 */
function DcNavAll($args){
    return \daicuo\Nav::all($args);
}
/**
 * 获取导航列表简单数组格式
 * @param array $args 查询条件（一维数组）
 * @return mixed null|array;
 */
function DcNavOption($args){
    return \daicuo\Nav::option($args);
}
/**************************************************权限组件***************************************************/
/**
 * 获取导航列表简单数组格式
 * @return array;
 */
function DcRolesOption(){
    $options = [];
    foreach(\daicuo\Auth::get_roles() as $role){
        $options[$role] = lang($role);
    }
    return $options;
}
/**************************************************视频组件***************************************************/
/**
 * 调用播放器
 * @param array $options  播放器参数，数组格式，
 * @return string
 */
function DcPlayer($options=[]){
    $video = new \daicuo\Video();
    return $video->player($options);
}
/**************************************************分页组件***************************************************/
/**
 * 解析页码为HTML
 * @param $index number 当前页
 * @param $rows number 每页数量 
 * @param $total number 总记录数
 * @param $path string URL路径
 * @param $query array URL额为参数
 * @param $fragment string URL锚点 
 * @param $varpage string 分页变量
 * @return string
 */
function DcPage($indexpage=1, $rows=10, $total=1, $path='', $query=[], $fragment='', $varpage='page'){
    $page = new \page\Bootstrap('', $rows, $indexpage, $total, false, [
        'var_page' => $varpage,
        'path' => str_replace('%5BPAGE%5D','[PAGE]',$path),//url('home/index/index','','')
        'query' => $query,
        'fragment' => $fragment,
    ]);
    return $page->render();
}
function DcPageSimple($pageIndex, $totalPage=1, $path=''){//&raquo;
    if($totalPage < 1){
        return '';
    }
    $path = str_replace('%5BPAGE%5D','[PAGE]',$path);//将转义后的方括号[page]
    if($pageIndex == 1){
        $prev = '<li class="page-item disabled"><a class="page-link" href="javascript:;">&laquo;</a></li>';
    }else{
        if(strpos($path,'[PAGE]')){
            $url = str_replace('[PAGE]',($pageIndex-1),$path);
        }else{
            $url = $path.($pageIndex-1);
        }
        $prev = '<li class="page-item"><a class="page-link" href="' . htmlentities($url) . '">&laquo;</a></li>';
    }
    if($pageIndex < $totalPage){
        if(strpos($path,'[PAGE]')){
            $url = str_replace('[PAGE]',($pageIndex+1),$path);
        }else{
            $url = $path.($pageIndex);
        }
        $next = '<li class="page-item"><a class="page-link" href="' . htmlentities($url) . '">&raquo;</a></li>';
    }else{
        $next = '<li class="page-item disabled"><a class="page-link" href="javascript:;">&raquo;</a></li>';
    }
    return sprintf('<ul class="pagination pagination-lg">%s %s</ul>', $prev, $next);
}
/**************************************************系统常用函数***************************************************/
/**
 * 加载配置文件（PHP格式）
 * @access public
 * @param  string $file  配置文件名
 * @param  string $name  配置名（如设置即表示二级配置）
 * @param  string $range 作用域
 * @return mixed
 */
function DcLoadConfig($file, $name = '', $range = ''){
    return Config::load($file, $name, $range);
}
/**
 * 加载语言定义(不区分大小写)
 * @access public
 * @param  array|string $file 语言文件
 * @param  string $range      语言作用域
 * @return mixed
 */
function DcLoadLang($file, $range = ''){
    return Lang::load($file, $range);
}
/**
 * 生成表单元素
 * @param array $params 表单元素参数
 * @return string 返回渲染后的表单HTML代码
 */
function DcBuildForm($params=[]){
    return widget('common/Form/build', ['params' =>$params]);
}
/**
 * 生成表格数据
 * @param array $config格据参数
 * @return string 返回渲染后的表单HTML代码
 */
function DcBuildTable($config=[]){
    return widget('common/Table/build', ['config' =>$config]);
}
/**
 * 判断是否为数组(一维)
 * @param array $array 待验证的数组
 * @param bool $count 验证是否一维数组
 * @return bool true|false
 */
function DcIsArray($array, $count=false){
    if($count == false){
        return is_array($array);
    }
    if (count($array) == count($array, 1)) {
        return true;
    } else {
        return false;
    }
}
/**************************************************字符串处理***************************************************/
//字符串截取
function DcSubstr($str, $start=0, $length, $suffix=true, $charset="UTF-8"){
    $str = trim($str);
    if( function_exists('mb_strimwidth') ){
        if($suffix){
            return mb_strimwidth($str, $start, $length*2, '...', $charset);
        }
        return mb_strimwidth($str, $start, $length*2, '', $charset);
    }
    return @substr($str, $start, $length);
}
//字符串安全输出
function DcHtml($string){
    return htmlspecialchars(trim($string), ENT_QUOTES);
}
//字符串作比较输出
function DcDefault($value, $default, $string_active='active', $string_empty=''){
  if($value == $default){
    return $string_active;
  }
  return $string_empty;
}
//空值输出
function DcEmpty($value, $default=''){
    return !empty($value) ? $value : $default;
}
//BOOL输出
function DcBool($value, $default=''){
    return DcDefault($value, 'true', $default);
}
//将参数组装为数组
function DcParseArgs($args,$defaults = ''){
	if ( is_array( $args ) ){
        //如果是数组则不转换
		$r =& $args;
    }else{
        //将接收的字符串转换为数组
		parse_str( $args, $r );
    }
	if ( is_array( $defaults ) ){
		return array_merge( $defaults, $r );
    }
	return $r;
}
/**************************************************Array、Xml、Json***************************************************/
//XML转数组
function DcXmlUnSerialize(&$xml, $isnormal = FALSE) {
    $xml_parser = new \net\Xml($isnormal);
    $data = $xml_parser->parse($xml);
    $xml_parser->destruct();
    return $data;
}
//数组转XML
function DcXmlSerialize($arr, $htmlon = FALSE, $isnormal = FALSE, $level = 1) {
    $s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
    $space = str_repeat("\t", $level);
    foreach($arr as $k => $v) {
        if(!is_array($v)) {
            $s .= $space."<item id=\"$k\">".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</item>\r\n";
        } else {
            $s .= $space."<item id=\"$k\">\r\n".DcXmlSerialize($v, $htmlon, $isnormal, $level + 1).$space."</item>\r\n";
        }
    }
    $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
    return $level == 1 ? $s."</root>" : $s;
}
/**
 * Json数据序列化为字符串
 * @params array $json json数据源
 * @return string
 */
function DcJsonToSerialize($json){
    if($json_array = json_decode($json, true)){
        return serialize($json_array);
    }
    return $json;
}
/**
 * 将序列化字符串转化为json
 * @params array $string 序列化后的数据
 * @return string
 */
function DcSerializeToJson($string){
    $array = unserialize($string);
    if(is_array($array)){
        return json_encode($array);
    }
    return $string;
}
/**
 * 将数组序列化为字符串
 * @params array $array 序列化后的数据
 * @return string
 */
function DcArraySerialize($array){
    if(is_array($array)){
        return serialize($array);
    }
    return $array;
}
/**
 * 二维数组根据字段进行排序
 * @params array $array 需要排序的数组
 * @params string $field 排序的字段
 * @params string $sort 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
 */
function DcArraySequence($array, $field, $sort = 'SORT_DESC'){
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}
/**
 * 在数据列表中搜索
 * @param array $list 数据列表
 * @param mixed $condition 查询条件 支持 array('name'=>$value) 或者 name=$value
 * @param string $key 要返回的字段值
 * @return array|value
 */
function DcArraySearch($array, $condition, $key=''){
    $array_search = list_search($array, $condition);
    if($key){
        return $array_search[0][$key];
    }
    return $array_search;
}
/**************************************************采集内核***************************************************/
function DcCurl($useragent='auto', $timeout=10, $url, $post_data='', $referer='', $headers='', $cookie='', $proxy=''){
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);//301 302
    curl_setopt ($ch, CURLOPT_ENCODING, "");//乱码是因为返回的数据被压缩过了，在curl中加上一项参数即可
    //useragent 
    //https://blog.csdn.net/u012175089/article/details/61199238
    //https://blog.csdn.net/learningITwell/article/details/78842439
    if($useragent == 'windows'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows;U;WindowsNT6.1;en-us)AppleWebKit/534.50(KHTML,likeGecko)Version/5.1Safari/534.50');
    }elseif($useragent == 'linux'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
    }elseif($useragent == 'ios'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh;U;IntelMacOSX10_6_8;en-us)AppleWebKit/534.50(KHTML,likeGecko)Version/5.1Safari/534.50');
    }elseif($useragent == 'iphone'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_2 like Mac OS X; zh-CN) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/14F89 UCBrowser/10.9.17.807 Mobile');
    }elseif($useragent == 'android'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 7.1.1; zh-cn; OPPO R11st Build/NMF26X) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.134 Mobile Safari/537.36 OppoBrowser/4.6.5.3');
    }    
    //是否post
    if(is_array($post_data)){
        curl_setopt($ch, CURLOPT_POST, 1);// post数据
        if($headers[0] == 'Content-Type: application/json'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));    // post的变量
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);    // post的变量
        }
    }
    //是否伪造来路
    if($referer){
        curl_setopt ($ch, CURLOPT_REFERER, $referer);
    }
    //是否headers
    if(is_array($headers)){
        //$headers = array('X-FORWARDED-FOR:28.58.88.10','CLIENT-IP:225.28.58.32');//构造IP
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
    }    
    //是否cookie
    if($cookie){
        curl_setopt ($ch, CURLOPT_COOKIE, $cookie);
    }
    //IP代理
    if($proxy){
        curl_setopt ($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt ($ch, CURLOPT_PROXYPORT, "80");
        //curl_setopt ($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
        //curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        //curl_setopt ($ch, CURLOPT_PROXYUSERPWD,'testuser:pass');
    }    
    //https自动处理
    $http = parse_url($url);
    if($http['scheme'] == 'https'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    $content = curl_exec($ch);
    curl_close($ch);
    //
    if($content){
        return $content;
    }
    return false;
}
/*快速正则规则*/
function DcPregMatch($rule,$html){
    $arr = explode('$$$',$rule);
    if(count($arr) == 2){
      preg_match('/'.$arr[1].'/', $html, $data);
        return $data[$arr[0]].'';
    }else{
      preg_match('/'.$rule.'/', $html, $data);
        return $data[1].'';
    }
}
/**************************************************数据转化***************************************************/
/**
 * 普通数据转一对多关系
 * @param array $data 待转化的数据,通常为post提交的一维数组数据
 * @param string $fields 需要转化的字段,多个用逗号分隔或传入数组
 * @param string $prefix 关联表名
 * @return array 转化后的数据
 */
function DcDataToMany($data, $fields='', $prefix='user_meta'){
    if(empty($data) || empty($fields)){
        return $data;
    }
    if( is_string($fields) ){
        $fields = explode(',', $fields);
    }
    foreach($fields as $key=>$field){
        if( isset($data[$field]) ){
            $data[$prefix][$key][$prefix.'_key'] = $field;
            $data[$prefix][$key][$prefix.'_value'] = $data[$field];
        }
        unset($data[$field]);
    }
    return $data;
}
/**
 * 一对多关系转普通数据
 * @param array $data 待转化的数据,通常为数据库查询后的数据
 * @param string $prefix 待修改的关联表名(可以理解为修改器)
 * @return array 转化后的数据/一维数组
 */
function DcManyToData($data, $prefix='user_meta'){
    if(empty($data)){
        return $data;
    }
    $data_meta = array();
    foreach($data[$prefix] as $value){
        $data_meta[$value[$prefix.'_key']] = $value[$prefix.'_value'];
    }
    unset($data[$prefix]);
    return array_merge($data, $data_meta);
}
/**
 * 普通数据转一对一关系
 * @param array $data 待转化的数据,通常为post提交的一维数组数据
 * @param string $fields 需要转化的字段,多个用逗号分隔或传入数组
 * @param string $prefix 关联表名
 * @return array 转化后的数据
 */
function DcDataToOne($data, $fields='', $prefix='term_much'){
    if(empty($data) || empty($fields)){
        return $data;
    }
    if( is_string($fields) ){
        $fields = explode(',', $fields);
    }
    foreach($fields as $key=>$field){
        if( isset($data[$field]) ){
            $data[$prefix][DcHtml($field)] = $data[$field];
        }
        unset($data[$field]);
    }
    return $data;
}
/**
 * 一对一关系转普通数据(模型定义关联未绑定时使用)
 * @param array $data 待转化的数据,通常为数据库查询后的数据
 * @param string $prefix 待修改的关联表名(可以理解为修改器)
 * @return array 转化后的数据/一维数组
 */
function DcOneToData($data, $prefix='term_much'){
    if(empty($data)){
        return $data;
    }
    $data_one = array();
    foreach($data[$prefix] as $key=>$value){
        $data_one[$key] = $value;
    }
    unset($data[$prefix]);
    return array_merge($data, $data_one);
}
/**
 * 别名唯一值验证
 * @param string $table 待验证的表名
 * @param string $value 待验证别名
 * @param string $id 主键ID
 * @return string 不是唯一值时自动添加-**
 */
function DcSlugUnique($table, $value, $id=0){
    if( empty($value) ){
        return uniqid();
    }
    $count = db($table)->where([$table.'_id'=>['neq',$id]])->where($table.'_slug',[['eq',$value],['like',$value.'-%'],'or'])->fetchSql(false)->count();
    if($count){
        $value = $value.'-'.($count+1);
    }
    return $value;
}
/**
* 格式化with参数为数组
* @param mixed array或string 支持表名转驼峰
* @return array
*/
function DcWith($with){
    if($with){
        if( is_string($with) ){
            $with = explode(',', $with);
        }
        foreach($with as $key=>$value){
            if(strpos($value, '_')){
                $with[$key] = camelize($value);
            }
        }
    }
    return $with;
}
/****************************************************ThinkPhp配置***************************************************/
//获取系统配置.支持多级层次
function DcConfig($config_name){
    $data = config();
    foreach (explode('.', $config_name) as $key => $val) {
        if (isset($data[$val])) {
            $data = $data[$val];
        } else {
            $data = null;
            break;
        }
    }
    return $data;
    /*$count = count($arrKey);
    $config[0] = config($arrKey[0]);
    if($count == 1){
        return $config[0];
    }
    for($i=1;$i<$count;$i++){
        $config[$i] = $config[$i-1][$arrKey[$i]];
    }
    return $config[($count-1)];*/
}
/**************************************************ThinkPhp验证器***************************************************/
//验证器独立验证
function DcCheck($data = [], $controll='Term', $scene='', $layer='validate'){
    $validate = validate($controll, $layer);
    if(!$validate->scene($scene)->check($data)){
        config('daicuo.error', $validate->getError());
        return false;
    }
    return true;
}
/**************************************************ThinkPhp钩子***************************************************/
/**
* 动态添加行为扩展到某个标签
* @access public
* @param  string $tag      标签名称
* @param  mixed  $behavior 行为名称
* @param  bool   $first    是否放到开头执行
* @return void
*/
function DcHookAdd($tag, $behavior, $first = false){
    \think\Hook::add($tag, $behavior, $first);
}
/**
 * 监听标签的行为
 * @access public
 * @param  string $tag    标签名称
 * @param  mixed  $params 传入参数
 * @param  mixed  $extra  额外参数
 * @param  bool   $once   只获取一个有效返回值
 * @return mixed
 */
function DcHookListen($tag, &$params = null, $extra = null, $once = false){
    \think\Hook::listen($tag, $params, $extra, $once);
}
/**
 * 执行某个行为
 * @access public
 * @param  mixed  $class  要执行的行为
 * @param  string $tag    方法名（标签名）
 * @param  mixed  $params 传入的参数
 * @param  mixed  $extra  额外参数
 * @return mixed
 */
function DcHookExec($class, $tag = '', &$params = null, $extra = null){
    \think\Hook::exec($class, $tag, $params, $extra);
}
/**************************************************ThinkPhp缓存***************************************************/
// 缓存标识函数、支持清空缓存(value=false)
function DcCache($key, $value='', $time=0){
    if(!$key){
        return false;
    }
    if(is_null($value)){
        return Cache::rm($key);
    }
    if($value === false || $value == 'clear'){
        return Cache::clear();
    }
    if($value){
        return Cache::set($key, $value, $time);
    }
    return Cache::get($key);
}
// 缓存标签函数、支持清空缓存(value=false)
function DcCacheTag($tag, $key, $value='', $time=0){
    if(!$tag){
        return false;
    }
    if(!$key){
        return false;
    }
    if($key == 'clear' && $value == ''){
        return Cache::clear($tag);
    }
    if($value === false || $value == 'clear'){
        return Cache::clear($tag);//DcCacheTag('tag','clear');
    }
    if($value){
        return Cache::tag($tag)->set($key, $value, $time);//DcCacheTag('tag','key','value',60);
    }
    return Cache::get($key);
}
/**
 * 数据库结果处理后触发删除缓存
 * @param int|obj $result     结果记录数或者数据集
 * @param string  $cacheSign  缓存标记（标识名或者标签名），
 * @param string  $delType    缓存删除方式(key|tag)，
 * @return $result
 */
function DcCacheResult($result, $cacheSign, $delType='key'){
    if($result && $cacheSign){
        if($delType == 'tag'){
            DcCacheTag($cacheSign, 'tag', false);
        }else{
            DcCache($cacheSign, NULL);
        }
    }
    return $result;
}
/**
 * 生成缓存KEY名
 * @param string|array $value 缓存名格式化
 * @return string
 */
function DcCacheKey($value){
    if(is_array($value)){
        return md5(serialize($value));
    }
    return md5($value);
}
/**************************************************ThinkPhp模板、路径***************************************************/
//系统根目录
function DcRoot(){
    $base = Request::instance()->root();
    return ltrim(dirname($base), DS).'/';
}
//生成站内链接
function DcUrl($url = '', $vars = '', $suffix = true){
    if(config('common.app_domain') == 'on'){
        return strip_tags(url($url, $vars, $suffix, true));
    }else{
        return strip_tags(url($url, $vars, $suffix, false));
    }
}
//插件管理路径
function DcUrlAddon($vars = '', $suffix = true){
    return '../addon/index?'.http_build_query($vars);
    //return DcUrl('admin/addon/index', $vars, $suffix);
}
//模板存放路径
function DcViewPath($module, $isMobile){
    return 'apps/'.$module.'/theme/'.DcTheme($module,$isMobile).'/';
}
//模板主题目录
function DcTheme($module='home', $isMobile=false){
    if($isMobile){
        return DcEmpty(config($module.'.theme_wap'),config('common.wap_theme'));
    }
    return DcEmpty(config($module.'.theme'),config('common.site_theme'));
}
/**************************************************ThinkPhp数据模型操作***************************************************/
/**
 * 将数据添加至数据库
 * @param string $name 资源地址(common/Nav)
 * @param array $data 待写入数据(关联写入则包含二维数组)
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/user_meta
 * @return int 返回自增ID或0
 */
function DcDbSave($name, $data=[], $relationTables=''){
    $model = model($name);
    //获取主键
    $pk = $model->getPk();
    //是否需要关联新增
    if($relationTables){
        //基础数据
        $dataBase = [];
        $tableBase = $model->getTableFields();
        foreach($tableBase as $key=>$value){
            if( isset($data[$value]) ){
                $dataBase[$value] = $data[$value];
            }
        }
        //$model->allowField(true)->save($dataBase);
        $model->data($dataBase, true)->allowField(true)->isUpdate(false)->save();
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        foreach($relationTables as $key=>$tableName){
            if($relationData = $data[$tableName]){
                $relationTable = camelize($tableName);
                //关联新增方式
                if( DcIsArray($relationData, true) ){
                    $model->$relationTable()->save($relationData);
                }else{
                    $model->$relationTable()->saveAll($relationData);
                }
            }
        }
    }else{
        $model->data($data, true)->allowField(true)->isUpdate(false)->save();
    }
    return $model->$pk;
}
/**
 * 删除数据
 * @param string $name 资源地址(common/Nav)
 * @param array $where 查询条件
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/如:user_meta
 * @return null|obj 不为空时返回修改后的obj
 */
function DcDbDelete($name, $where=[], $relationTables=''){
    $model = model($name);
    $modelPk = $model->getPk();//获取模型主键
    $result = array();
    //是否需要关联删除
    if($relationTables){
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        //驼峰转化
        foreach($relationTables as $key=>$value){
            $relationTables[$key] = camelize($value);
        }
        //查询数据
        $data = $model->with($relationTables)->where($where)->find();
        //无结果
        if( is_null($data) ){
            return null;
        }
        //删除基础数据
        $result[0] = $data->delete();
        //删除关联数据
        foreach($relationTables as $key=>$tableName){
            array_push($result, $data->$tableName()->delete());
        }
    }else{
        $data = $model->get($where);
        if( is_null($data) ){
            return null;
        }
        $result[0] = $data->delete();
    }
    //缓存处理
    if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) ){
        if( !is_null($info) ){
            DcCacheTag($modelPk.'_'.$data->$modelPk, 'clear', 'clear');
            //DcCacheTag($name.'/Item', 'clear', 'clear');
        }
    }
    //删除结果
    $data->RESULT = $result;
    return $data;
}
/**
 * 修改数据
 * @param string $name 资源地址(common/Nav)
 * @param array $where 更新条件
 * @param array $data 待写入数据(关联写入则包含二维数组)
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/user_meta
 * @return obj|null 不为空时返回修改后的obj
 */
function DcDbUpdate($name, $where=[], $data=[], $relationTables=''){
    $model = model($name);
    $modelPk = $model->getPk();//获取模型主键
    unset($data[$modelPk]);//数据主键过滤
    //数据查询
    $info = $model->get($where);
    if( is_null($info) ){
        return null;
    }
    //基础数据表更新
    $info->allowField(true)->isUpdate()->save($data);
    //关联数据表更新
    if($relationTables){
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        //关联操作
        foreach($relationTables as $key=>$tableName){
            //关联数据验证
            if( empty($data[$tableName]) ){
                break;
            }
            //驼峰表名
            $relationTable = camelize($tableName);
            //采用哪种关联模式(由data关联表的数据决定,二维数组则是一对多)
            if( DcIsArray($data[$tableName], true) ){
                //一对一关联
                if(is_null($info->$relationTable)){
                    //无关联数据时新增
                    $info->$relationTable()->save($data[$tableName]);
                }else{
                    //已有关联数据时直接修改
                    $info->$relationTable->isUpdate()->save($data[$tableName]);
                }
            }else{
                //一对多关联
                $info->$relationTable()->delete();
                $info->$relationTable()->saveAll($data[$tableName]);
            }
        }
    }
    //缓存处理
    if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) ){
        if( !is_null($info) ){
            DcCacheTag($modelPk.'_'.$info->$modelPk, 'clear', 'clear');
            //DcCacheTag($name.'/Item', 'clear', 'clear');
        }
    }
    return $info;
}
/**
 * 模型的get方法查询单条数据
 * @param string $name 资源地址(common/Nav)
 * @param array $where 查询条件
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/如:user_meta
 * @return obj|null 不为空时返回obj
 */
function DcDbGet($name, $where=[], $relationTables=''){
    $model = model($name);
    //是否需要关联预载入查询
    if($relationTables){
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        //驼峰转化
        foreach($relationTables as $key=>$value){
            $relationTables[$key] = camelize($value);
        }
        //查询数据
        return $model->get($where, $relationTables);
    }else{
        return $model->get($where);
    }
}
/**
 * 模型的all方法查询多条数据
 * @param string $name 资源地址(common/Nav)
 * @param array $where 查询条件
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/如:user_meta
 * @return obj|null 不为空时返回obj
 */
function DcDbAll($name, $where=[], $relationTables=''){
    $model = model($name);
    //是否需要关联预载入查询
    if($relationTables){
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        //驼峰转化
        foreach($relationTables as $key=>$value){
            $relationTables[$key] = camelize($value);
        }
        //查询数据
        return $model->all($where, $relationTables);
    }else{
        return $model->all($where);
    }
}
/**
 * 查询单条数据
 * @param string $name 资源地址(common/Nav)
 * @param array $params 查询参数
 * @return obj|null 不为空时返回obj
 */
function DcDbFind($name, $params){
    $model = model($name);
    $modelPk = $model->getPk();
    $cacheExpire = config('cache.expire_detail');
    //
    $args = array();
    $args['field'] = '*';
    $args['where'] = [];
    $args['whereOr'] = [];
    $args['wheretime'] = '';
    //
    $args['join'] = [];
    $args['union'] = [];
    $args['view'] = [];
    //
    $args['relation'] = '';
    $args['with'] = [];
    $args['bind'] = '';
    //
    $args['sort'] = '';
    $args['order'] = '';
    //
    $args['fetchSql'] = false;
    $args['cache'] = true;
    //合并参数
    if($params){
        $args = array_merge($args, $params);
    }
    //缓存管理
    if($args['cache'] && (false == $args['fetchSql']) ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            //缓存前缀
            $args['cacheKey'] = DcCacheKey($args);
            //无缓存的时候返回false
            if( $info = DcCache($args['cacheKey']) ){
                return $info;
            }
        }
    }
    //非关联条件
    if($args['fetchSql']){
        $model->fetchSql($args['fetchSql']);
    }
    if($args['field']){
        $model->field($args['field']);
    }
    if($args['where']){
        $model->where($args['where']);
    }
    if($args['whereOr']){
        $model->whereOr($args['whereOr']);
    }
    if($args['wheretime']){
        $model->wheretime($args['wheretime']);
    }
    if($args['join']){
        $model->join($args['join']);
    }
    if($args['union']){
        $model->union($args['union']);
    }
    if($args['view']){
        $model->view($args['view']);
    }
    if($args['relation']){
        $model->relation($args['relation']);
    }
    if($args['with']){
        $model->with($args['with']);
    }
    if($args['bind']){
        $model->bind($args['bind']);
    }
    if($args['sort'] && $args['order'] ){
        $model->order($args['sort'].' '.$args['order']);
    }
    //查询数据库
    $info = $model->find();
    //无结果
    if( is_null($info) ){
        return null;
    }
    //sql语句生成
    if( is_string($info) ){
        return $info;
    }
    //缓存写入
    if($args['cache'] && (false == $args['fetchSql']) && $args['cacheKey'] ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            DcCacheTag($modelPk.'_'.$info->$modelPk, $args['cacheKey'], $info, $cacheExpire);
        }
    }
    return $info;
}
/**
 * 查询多条数据
 * @param string $name 资源地址(common/Nav)
 * @param array $params 查询参数
 * @return obj|null|string 分页模询统一返回obj,sql语句为对象属性|为空时null不为空时obj,sql语句返回字符串
 */
function DcDbSelect($name, $params){
    $model = model($name);
    $cacheExpire = config('cache.expire_item');
    //
    $args = array();
    $args['field'] = '*';
    $args['where'] = [];
    $args['whereOr'] = [];
    $args['wheretime'] = '';
    //
    $args['group'] = '';
    $args['having'] = '';
    $args['join'] = [];
    $args['union'] = [];
    $args['view'] = [];
    //
    $args['hasWhere'] = [];
    $args['relation'] = '';
    $args['with'] = [];
    $args['bind'] = '';
    //
    $args['limit'] = 0;
    $args['page'] = 0;
    $args['sort'] = '';
    $args['order'] = '';
    $args['force'] = '';
    //
    $args['lock'] = false;
    $args['distinct'] = false;
    $args['paginate'] = [];
    /*$args['paginate'] = [
        'list_rows' => 5,
        'page' => 2,
        'path' => '',
        'query'=> [],
        'fragment'=>'',
        'var_page' => 'page',
        'type'     => 'bootstrap',
    ];*/
    //
    $args['cache'] = true;
    $args['fetchSql'] = false;
    //合并参数
    if($params){
        $args = array_merge($args, $params);
    }
    //缓存管理
    if($args['cache'] && (false == $args['fetchSql']) ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            $args['cacheKey'] = DcCacheKey($args);
            if( $list = DcCache($args['cacheKey']) ){
                return $list;
            }
        }
    }
    //取消hasWhere关联条件(因为只能一个表 有局恨性)
    if($args['field']){
        $model->field($args['field']);
    }
    if($args['where']){
        $model->where($args['where']);
    }
    if($args['whereOr']){
        $model->whereOr($args['whereOr']);
    }
    if($args['wheretime']){
        $model->wheretime($args['wheretime']);
    }
    if($args['group']){
        $model->group($args['group']);
    }
    if($args['having']){
        $model->having($args['having']);
    }
    if($args['join']){
        $model->join($args['join']);
    }
    if($args['union']){
        $model->union($args['union']);
    }
    if($args['view']){
        $model->view($args['view']);
    }
    if($args['relation']){
        $model->relation($args['relation']);
    }
    if($args['with']){
        $model->with($args['with']);
    }
    if($args['bind']){
        $model->bind($args['bind']);
    }
    if($args['limit']){
        $model->limit($args['limit']);
    }
    if($args['page']){
        $model->page($args['page']);
    }
    if($args['sort'] && $args['order'] ){
        $model->order($args['sort'].' '.$args['order']);
    }
    if($args['force']){
        $model->force($args['force']);
    }
    if($args['lock']){
        $model->lock($args['lock']);
    }
    if($args['distinct']){
        $model->distinct($args['distinct']);
    }
    if($args['fetchSql']){
        $model->fetchSql($args['fetchSql']);
    }
    if($args['paginate']){
        $list = $model->paginate($args['paginate']);
    }else{
        $list = $model->select();
        if( is_string($list) ){
            return $list;
        }
    }
    if($list->isEmpty()){
        return null;
    }
    //缓存写入
    if($args['cache'] && (false == $args['fetchSql']) && $args['cacheKey'] ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            //str_replace('/','',strtolower(strrchr($name,"/")))
            DcCacheTag($name.'/Item', $args['cacheKey'], $list, $cacheExpire);
        }
    }
    return $list;
}
/*---------------------------------------------ThinkPhp数据库操作------------------------------------------------------------*/
/**
 * 数据查询多个
 * @param string $name Model名称
 * @param array $where 查询条件
 * @param array $params 查询参数
 * @return obj 数据集
 */
function dbSelect($name='', $where=[], $params=[], $cache=[]){
    if(!$name){
        return false;
    }
    //缓存参数初始化
    if(is_array($cache)){
        $cache = array_merge(['key'=>false,'time'=>false,'tag'=>false], $cache);
    }else{
        $cache = ['key'=>false,'time'=>false,'tag'=>false];
    }
    //参数初始化
    $params = array_merge(['fetchSql'=>false,'field'=>'*'], $params);
    //分页
    if($params['page']){
        return model($name)->field($params['field'])->where($where)->order($params['sort'].' '.$params['order'])->fetchSql($params['fetchSql'])->cache($cache['key'],$cache['time'],$cache['tag'])->paginate($params['paginate']);
    }else{
        return model($name)->field($params['field'])->where($where)->limit($params['limit'])->order($params['sort'].' '.$params['order'])->fetchSql($params['fetchSql'])->cache($cache['key'],$cache['time'],$cache['tag'])->select();
    }
}
/**
 * 数据查询单个 格式：[模块/]控制器
 * @param string $name Model名称
 * @param array $where 查询条件
 * @param string $whereOr 查询条件
 * @param bool $fetchSql 显示查询语句
 * @return obj 数据集
 */
function dbFind($name='', $where=[], $whereOr='', $cache=[], $fetchSql=false){
    if(!$name){
        return false;
    }
    //缓存参数初始化
    if(is_array($cache)){
        $cache = array_merge(['key'=>false,'time'=>false,'tag'=>false], $cache);
    }else{
        $cache = ['key'=>false,'time'=>false,'tag'=>false];
    }
    //缓存条件处理
    if($cache['key'] && $cache['time']){
        return model($name)->where($where)->whereOr($whereOr)->fetchSql($fetchSql)->cache($cache['key'],$cache['time'])->find();
    }else{
        return model($name)->where($where)->whereOr($whereOr)->fetchSql($fetchSql)->find();
    }
}
/**
 * 写入多条数据自动判断新增与修改
 * @param string $name Model名称
 * @param array $list 数据、二维数组
 * @return intval 返回数据集;
 */
function dbWriteAuto($name='', $list){
    return model($name)->allowField(true)->saveAll($list);
    //返回的是包含新增模型（带自增ID）的数据集（数组） 当数据中存在主键的时候会认为是更新操作 否则为新增
}
/**
 * 更新某个字段的值
 * @param string $name Model名称
 * @param array $where 条件 
 * @param string field 字段名
 * @param string value 字段值
 * @return intval 返回影响数据的条数，没修改任何数据字段返回 0;
 */
function dbUpdateField($name='',$where=[], $field, $value){
    return model($name)->where($where)->setField($field,$value);
}
/**
 * 自增某字段的值
 * @param string $name Model名称
 * @param array $where 条件  
 * @param string field 字段名
 * @param string num 递增值
 * @param string time 延迟更新时长
 * @return intval 返回影响数据的条数;
 */
function dbUpdateInc($name='',$where=[], $field, $num=1, $time=0){
    return model($name)->where($where)->setInc($field, $num, $time);
}
/**
 * 自减某字段的值
 * @param string $name Model名称
 * @param array $where 条件  
 * @param string field 字段名
 * @param string num 递增值
 * @param string time 延迟更新时长
 * @return intval 返回影响数据的条数;
 */
function dbUpdateDec($name='',$where=[], $field, $num=1, $time=0){
    return model($name)->where($where)->setDec($field, $num, $time);
}
/**
 * 批量更新数据(批量更新仅能根据主键值进行更新，其它情况请使用foreach遍历更新)
 * @param string $name Model名称 
 * @param array $list 数据、二维数组(如果不包含主键则需要复合主键才可以成功)
 * @return intval 影响条数;
 */
function dbUpdateAll($name='', $list=[]){
    return model($name)->allowField(true)->isUpdate()->saveAll($list);//强制更新操作
}
/**
 * 更新数据
 * @param string $name Model名称
 * @param array $where 条件  
 * @param array $data 数据
 * @return intval 返回修改成功的条数;
 */
function dbUpdate($name='', $where=[], $data=[]){
    return model($name)->allowField(true)->save($data, $where);
    //return db($table)->where($where)->update($data);
}
/**
 * 删除数据
 * @param string $name Model名称
 * @param array $where 条件
 * @return intval 返回影响数据的条数，没有删除返回 0;
 */
function dbDelete($name='',$where){
    return model($name)->where($where)->delete();
}
/**
 * 添加多条数据
 * @param string $name Model名称
 * @param array $data 数据、二维数组
 * @return obj|null 返回数据集;
 */
function dbInsertAll($name='', $list){
    $status = model($name)->allowField(true)->saveAll($list, false);
    if($status->isEmpty()){
        return null;
    }
    return $status;
    //返回的是包含新增模型（带自增ID）的数据集（数组） 当数据中存在主键的时候会认为是更新操作 加上false强制新增
    //return db($name)->insertAll($data);
}
/**
 * 添加一条数据
 * @param string $name Model名称
 * @param array  $data  数据
 * @return intval 返回记录数;
 */
function dbInsert($name='', $data){
    $model = model($name);
    $model->data($data, true);
    return $model->allowField(true)->save();//返回的是写入的记录数
    //return db($name)->insertGetId($data);//返回添加数据的自增主键
    //return db($name)->insert($data);返回添加成功的条数
}
/**************************************************ThinkPHP扩展函数库***************************************************/
/**
 * 判断邮箱
 * @param string $str 要验证的邮箱地址
 * @return bool
 */
function is_email($str) {
    return preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $str);
}
/**
 * 判断手机号
 * @param string $num 要验证的手机号
 * @return bool
 */
function is_mobile($num) {
    return preg_match("/^1(3|4|5|6|7|8|9)\d{9}$/", $num);
}
/**
 * 判断用户名
 * 用户名支持中文、字母、数字、下划线，但必须以中文或字母开头，长度3-20个字符
 * @param string $str 要验证的字符串
 * @return bool
 */
function is_username($str) {
    return preg_match("/^[\x80-\xffA-Za-z]{1,1}[\x80-\xff_A-Za-z0-9]{2,19}+$/", $str);
}
/**
 * 读取文件
 * @param string $path 完整文件路径名
 * @return bool
 */
function read_file($path){
    return @file_get_contents($path);
}
/**
 * 写入文件
 * @param string $l1 完整文件路径名
 * @param string $l2 要写入文件的内容 
 * @return bool
 */
function write_file($l1, $l2=''){
    $dir = dirname($l1);
    if(!is_dir($dir)){
        mkdir_ss($dir);
    }
    return @file_put_contents($l1, $l2);
}
/**
 * 数组保存到文件
 * @param string $filename 完整文件路径名
 * @param string $arr 数组
 * @return bool
 */
function write_arr2file($filename, $arr=''){
    if(is_array($arr)){
        $con = var_export($arr,true);
    } else{
        $con = $arr;
    }
    $con = "<?php\nreturn $con;\n?>";//\n!defined('IN_MP') && die();\nreturn $con;\n
    write_file($filename, $con);
}
/**
 * 递归创件文件
 * @param string $dirs 完整文件路径名
 * @param string $mode 权限 
 * @return bool
 */
function mkdir_ss($dirs,$mode=0777) {
    if(!is_dir($dirs)){
        mkdir_ss(dirname($dirs), $mode);
        return @mkdir($dirs, $mode);
    }
    return true;
}
/**
 * 列出所有文件夹名
 * @param string $dir 完整文件夹路径
 * @return array
 */
function glob_basename($path = 'apps/home/theme/') {
    $list = glob($path.'*');
    foreach ($list as $i=>$file){
        $dir[] = basename($file);
    }    
    return $dir;
}
/**
 * 在数据列表中搜索
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
/*** 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array 
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root=0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array 返回排过序的列表数组
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, 'asc');
    }
    return $list;
}
/**
 +----------------------------------------------------------
 * 对查询结果集进行排序
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型
 * asc正向排序 desc逆向排序 nat自然排序
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function list_sort_by($list,$field, $sortby='asc') {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ( $refer as $key=> $val)
           $resultSet[] = &$list[$key];
       return $resultSet;
   }
   return false;
}
/**
* XSS漏洞过滤
* @param string $$val 待验证的字符串
* @return string 去掉敏感信息的字符串
*/
function remove_xss($val) {
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);
   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}
/**
* 下划线转驼峰
* @param string $uncamelized_words 下划线样式的字符串
* @param string $separator 分隔符/默认'_'
* @return string 驼峰样式的字符串
* step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
* step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
*/
function camelize($uncamelized_words, $separator='_'){
    $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
}
/**
* 驼峰命名转下划线命名
* @param string $camelCaps 驼峰命名字符串
* @param string $separator 分隔符/默认'_'
* @return string 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
*/
function uncamelize($camelCaps, $separator='_'){
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}
/**************************************************无限层级分类***************************************************/
/**
 * 获取指定分类的所有子集(递归法)
 * @param array $categorys 数组列表
 * @param int $catId 主键ID值
 * @param int $level 层级记录数
 * @param int $pk 主键名称
 * @param int $pid 父级名称
 * @return array;
 */
function get_childs($categorys, $catId=0, $level=1, $pk='term_id', $pid='term_much_parent'){
    $subs = array();
    foreach($categorys as $item){
        if($item[$pid] == $catId){
            $item['level'] = $level;
            $subs[] = $item;
            $subs = array_merge($subs, get_childs($categorys, $item[$pk], $level+1, $pk, $pid) );
        }
    }
    return $subs;
}
/**
 * 获取某一个子类的所有父级(递归法)
 * @param array $categorys 数组列表
 * @param int $parentId 父级ID值
 * @param int $pk 主键名称
 * @param int $pid 父级名称
 * @return mixed null|array;
 */
function get_parents($categorys, $parentId, $pk='term_id', $pid='term_much_parent'){
    $tree = array();
    foreach($categorys as $item){
        if($item[$pk] == $parentId){
            $tree[] = $item;
            $tree = array_merge($tree, get_parents($categorys, $item[$pid], $pk, $pid) );
        }
    }
    return $tree;
}
/**
 * 将list_to_tree的树还原成带层维数的数据列表/用于表格展示
 * @param  array $tree  原来的树
 * @param  string $pkName 要添加符号的键名
 * @param  string $level 记录无限层级关系 
 * @param  string $child 孩子节点的键
 * @param  array  $list  过渡用的中间数组，
 * @return array 返回排过序的列表数组
 */
function tree_to_level($tree, $pkName='', $level=0, $child='_child', &$list = array()){
    if(is_array($tree)) {
        $icon   = '';
        if ($level > 0) {
            $icon = '|';
            for ($i=0; $i < $level; $i++) {
                //$icon .= '&nbsp;&nbsp;&nbsp;';
                $icon .= '─ ';
            }
            //$icon .= '├&nbsp;';
        }
        $refer = array();
        foreach ($tree as $value) {
            $reffer = $value;
            if($pkName){
                $reffer[$pkName] = $icon.$reffer[$pkName];
            }
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                $list[] = $reffer;
                tree_to_level($value[$child], $pkName, $level+1, $child, $list);
            }else{
                $list[] = $reffer;
            }
        }
    }
    return $list;
}
/**
 * 将原生数据集生成options的选项
 * @param array $list 原生数据
 * @param intval $pid 父级ID
 * @param intval $sid 选中ID
 * @param array $did 禁止选择
 * @param int $level 当前层数
 * @param array $config 初始配置  
 * @return string 返回格式化后的option选项
 */
function list_to_option($list = [], $pid = 0, $sid = 0, $did = [], $level = 0, $config=[]){
    $config_ = array_merge(['id'=>'op_id','pid'=>'nav_parent','name'=>'nav_text'], $config);
    $tree = new \daicuo\Tree($config_);
    return $tree->toOptions($tree->toTree($list, $pid, 0, $level), $sid);
}