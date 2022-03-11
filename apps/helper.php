<?php
/**************************************************视频模块***************************************************/
/**
 * 调用播放器
 * @version 1.4.0 首次引入
 * @param array $options 播放器参数，数组格式
 * @return string $string html代码
 */
function DcPlayer($options=[]){
    $video = new \daicuo\Video();
    return $video->player($options);
}

/**************************************************编辑器模块***************************************************/
/**
 * 调用编辑器解析函数
 * @version 1.6.0 首次引入
 * @param string $content 内容
 * @return mixed $mixed 解析后的内容
 */
function DcEditor($content=''){
    if(config('common.editor_name') != 'textarea'){
        if( $function = config(config('common.editor_name').'.editor_function') ){
            return call_user_func_array($function, [$content]);
        }
    }
    return $content;
}
/**
 * 根据框架配置获取当前编辑器路径
 * @version 1.6.0 首次引入
 * @return string $string 后台设置的编辑器路径
 */
function DcEditorPath(){
    if(config('common.editor_name') != 'textarea'){
        if( $editorPath = config(config('common.editor_name').'.editor_path') ){
            return $editorPath;
        }
    }
    return config('form_view.editor');
}
/**
 * 获取已安装的编辑器列表
 * @version 1.6.0 首次引入
 * @return array $array 普通数组列表（键名为模块名）
 */
function DcEditorOption(){
    $option = [];
    foreach(config('common.editor_list') as $editor){
        $option[$editor] = lang($editor);
    }
    return $option;
}

/**************************************************分页模块***************************************************/
/**
 * 解析页码为HTML
 * @version 1.1.0 首次引入
 * @param number $index 当前页
 * @param number $rows 每页数量 
 * @param number $total 总记录数
 * @param string $path URL路径
 * @param array $query URL额为参数
 * @param string $fragment URL锚点 
 * @param string $varpage 分页变量
 * @return string $string HTML代码
 */
function DcPage($index=1, $rows=10, $total=1, $path='', $query=[], $fragment='', $varpage='pageNumber'){
    $page = new \page\Bootstrap('', $rows, $index, $total, false, [
        'var_page' => $varpage,
        'path'     => str_replace('%5BPAGE%5D','[PAGE]',$path),//url('index/index/index','','')
        'query'    => $query,
        'fragment' => $fragment,
    ]);
    return $page->render();
}
/**
 * 单页分页代码HTML
 * @version 1.1.0 首次引入
 * @param number $index 当前页
 * @param number $total 总页数 
 * @return string $string HTML代码
 */
function DcPageSimple($page=1, $total=1, $path=''){
    //统一变量
    $path = str_replace('%5BPAGE%5D','[PAGE]', $path);
    //上一页按钮
    if ($page <= 1) {
        $preButton = '<li class="page-item disabled"><a class="page-link" href="javascript:;">&laquo;</a></li>';
    }else{
        $url = str_replace('[PAGE]', $page-1, $path); 
        $preButton = '<li class="page-item"><a class="page-link" href="' . htmlentities($url) . '">&laquo;</a></li>';
    }
    //下一页按钮
    if ($page == $total) {
        $nextButton = '<li class="page-item disabled"><a class="page-link" href="javascript:;">&raquo;</a></li>';
    }else{
        $url = str_replace('[PAGE]', $page+1, $path);  
        $nextButton = '<li class="page-item"><a class="page-link" href="' . htmlentities($url) . '">&raquo;</a></li>';
    }
    return sprintf('<ul class="pagination">%s %s</ul>', $preButton, $nextButton);
}
/**
 * 根据字段过滤分页参数、只保留默认两个
 * @version 1.6.0 首次引入
 * @param array $args 必需;参数列表
 * @return array $array 只返回字段中的条件语句
 */
function DcPageFilter($args=[]){
    if($args['limit'] && $args['page']){
        return [
            'list_rows' => $args['limit'],
            'page' => $args['page'],
        ];
    }
    return [];
}

/****************************************************配置模块***************************************************/
/**
 * 批量更新与新增动态配置、常用于需自动加载的配置
 * @version 1.6.0 首次引入
 * @param array $formData 必需;表单数据(key=>value)成对形式;默认：空
 * @param string $module 必需;模块名称;默认：common
 * @param string $controll 可选;控制器名;默认：NULL
 * @param string $action 可选;操作名;默认：NULL
 * @param int $order 可选;排序值;默认：0
 * @param string $autoload 可选;自动加载;默认：yes
 * @param string $status 可选;状态;默认：normal
 * @return array $array 数据集
 */
function DcConfigWrite($post=[], $module='common', $controll='config', $action='system', $order=0, $autoload='yes', $status='normal'){
    return \daicuo\Op::write($post, $module, $controll, $action, $order, $autoload, $status);
}
/**
 * 添加动态配置至数据库
 * @version 1.6.0 首次引入
 * @param array $data 必需;普通表单数组形式;默认：空
 * @return mixed $mixed 自增ID
 */
function DcConfigSave($post=[]){
    return model('common/Config','loglic')->write($post);
}
/**
 * 按条件删除一条动态配置
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed 查询结果（array|null）
 */
function DcConfigDelete($args=[]){
    return model('common/Config','loglic')->delete($args);
}
/**
 * 按条件获取一个配置列表
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed 查询结果（array|null）
 */
function DcConfigFind($args=[]){
    return model('common/Config','loglic')->get($args);
}
/**
 * 按条件获取多个配置列表
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed 查询结果（obj|null）
 */
function DcConfigSelect($args=[]){
    return model('common/Config','loglic')->select($args);
}

/**************************************************内容模块***************************************************/
/**
 * 按条件获取一个内容数据
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed 查询结果（array|null）
 */
function DcInfoFind($args=[]){
    return model('common/Info','loglic')->get($args);
}
/**
 * 获取多条内容数据
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed obj|array|null 查询结果
 */
function DcInfoSelect($args=[]){
    return model('common/Info','loglic')->select($args);
}

/**************************************************队列模块***************************************************/
/**
 * 按条件获取一个分类
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed 查询结果（array|null）
 */
function DcTermFind($args=[]){
    return model('common/Term','loglic')->get($args);
}
/**
 * 按ID快速获取一条队列信息
 * @version 1.6.0 首次引入
 * @param int $id 必需;Id值;默认：空
 * @return mixed $mixed 查询结果(obj|null)
 */
function DcTermFindId($id='', $cache=true){
    return \daicuo\Term::get_id($id, $cache);
}
/**
 * 获取多条分类数据
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed obj|array|null 查询结果
 */
function DcTermSelect($args=[]){
    return model('common/Term','loglic')->select($args);
}
/**
 * 获取分类列表checkbox关系
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed obj|null 
 */
function DcTermCheck($args=[]){
    $args = DcArrayArgs($args,[
        'controll'   => 'category',
        'fieldKey'   => 'term_id',
        'fieldValue' => 'term_name',
    ]);
    return \daicuo\Term::Option($args);
}
/**
 * 获取分类列表select关系
 * @version 1.6.0 优化调用参数
 * @version 1.5.20 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed null|array
 */
function DcTermOption($args=[]){
    $args = DcArrayArgs($args,[
        'controll'   => 'category',
        'fieldKey'   => 'term_id',
        'fieldValue' => 'term_name',
        'isSelect'   => true,
    ]);
    return \daicuo\Term::Option($args);
}
/**
 * 按条件获取队列设为导航栏的队列列表
 * @version 1.8.10 优化
 * @version 1.6.4 首次引入
 * @param array $args 必需;查询条件数组格式 {
 *     @type bool $cache 可选;是否缓存;默认：true
 *     @type string $result 可选;返回状态(array|tree|level);默认：tree
 *     @type string $status 可选;显示状态（normal|hidden）;默认：空
 *     @type string $module 可选;模型名称;默认：空
 *     @type string $controll 可选;控制器名称;默认：空
 *     @type string $action 可选;操作名称(navbar|navs);默认：空
 *     @type int $limit 可选;分页大小;默认：0
 *     @type string $sort 可选;排序字段名;默认：op_order
 *     @type string $order 可选;排序方式(asc|desc);默认：asc
 *     @type array $where 可选;自定义高级查询条件;默认：空
 * }
 * @return mixed 查询结果obj|null
 */
function DcTermNavbar($args=[])
{
    return model('common/Navs','loglic')->select($args);
}
/**
 * 通过termId获取所有子类ID
 * @version 1.8.10 优化
 * @version 1.6.0 首次引入
 * @param int $termId 必需;队列ID;默认：空
 * @param string $termControll 必需;队列类型(category|tag);默认：category
 * @param string $result 必需;返回类型(array|string);默认：array
 * @param string $termModule 可选;应用名;默认：空
 * @param string $termAction 可选;操作名;默认：空
 * @return mixed $mixed array|string
 */
function DcTermSubIds($termId,$termControll='category',$result='array',$termModule='',$termAction=''){
    if(!$termId){
        return null;
    }
    $subIds = [$termId];
    foreach(\daicuo\Term::childrens($termId, $termControll, $termModule,$termAction) as $key=>$value){
        array_push($subIds, $value['term_id']);
    }
    if($result == 'array'){
        return $subIds;
    }
    return implode(',', $subIds);
}
/**
 * 通过队列名获取队列ID
 * @version 1.6.0 首次引入
 * @param mixed $value 必需;队例字段值；默认：空
 * @param bool $cache 可选;是否缓存；默认：空
 * @param array $args 可选;附加参数；默认：空
 * @return int $int 父级ID值
 */
function DcTermNameToId($value='', $cache=true, $args=[]){
    $term = DcTermNameToIds($value,$cache,$args);
    return intval($term[0]);
}
/**
 * 多个队列名转化为多个队例ID
 * @version 1.6.0 首次引入
 * @param mixed $value 必需;多个队列名称,逗号分隔或数组(string|array)；默认：空
 * @param string $type 必需;队例类型(category|tag)；默认：category
 * @param array $args 可选;附加参数；默认：空
 * @return int $int 父级ID值
 */
function DcTermNameToIds($value='', $args=[]){
    if(!$value){
        return [0];
    }
    if(is_array($value)){
        $value = implode(',',$value);
    }
    $args = DcArrayArgs($args,[
        'controll' => 'category',
        'cache'    => true,
        'name'     => ['in', $value],
    ]);
    $args  = array_merge(DcArrayEmpty($args),['with'=>'']);
    $terms = model('common/Term','loglic')->select($args);
    return array_column($terms, 'term_id');
}
/**************************************************用户模块***************************************************/
/**
 * 按Id快速修改一个用户
 * @param int $id 必需;ID值;默认：空
 * @param array $data 必需;表单数据（一维数组）;默认：空 
 * @return mixed $mixed obj|null
 */
function DcUserUpdateId($id, $data){
    return \daicuo\User::update_id($id, $data);
}
/**
 * 获取当前用户登录信息
 * @version 1.6.0 首次引入
 * @return array $array 用户信息（未登录则为游客） 
 */
function DcUserCurrentGetId(){
    return \daicuo\User::get_current_user_id();
}
/**
 * 按ID快速获取一条用户数据
 * @version 1.6.0 首次引入
 * @param int $id 必需;Id值；默认：空
 * @param bool $cache 可选;是否缓存;默认：true
 * @return mixed $mixed 查询结果(obj|null)
 */
function DcUserFindId($id='', $cache=true){
    return model('common/User','loglic')->getId($id, $cache);
}
/**
 * 按条件获取一个用户数据
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed 查询结果（array|null）
 */
function DcUserFind($args=[]){
    return model('common/User','loglic')->get($args);
}
/**
 * 按条件获取多个用户数据
 * @version 1.6.0 首次引入
 * @param array $args 必需;查询条件数组格式;默认：空
 * @return mixed $mixed 查询结果（array|null）
 */
function DcUserSelect($args=[]){
    return model('common/User','loglic')->select($args);
}
/************************************用户META模块*******************************************************/
/**
 * 通过用户ID与metaKey快速增加一条自定义信息
 * @version 1.6.0 首次引入
 * @param string $userId 必需;用户ID;默认：空
 * @param string $metaKey 必需;自定义字段;默认：空
 * @param mixed $metaValue 必需;自定义字段段（int|array|string）;默认：空
 * @return int $int 影响条数
 */
function DcUserMetaSave($userId=0, $metaKey='', $metaValue=''){
    return \daicuo\User::save_user_meta($userId, $metaKey, $metaValue);
}
/**
 * 通过用户ID与metaKey快速删除自定义信息
 * @version 1.6.0 首次引入
 * @param string $userId 必需;用户ID;默认：空
 * @param string $metaKey 可选;自定义字段名为空时删除所有自定义字段;默认：空
 * @return int $int 影响条数
 */
function DcUserMetaDelete($userId=0, $metaKey=''){
    return \daicuo\User::delete_user_meta($userId, $metaKey);
}
/**
 * 通过用户ID与metaKey快速修改一条自定义信息
 * @version 1.6.0 首次引入
 * @param string $userId 必需;用户ID;默认：空
 * @param string $metaKey 必需;自定义字段;默认：空
 * @param mixed $metaValue 必需;自定义字段段（int|array|string）;默认：空
 * @return int $int 影响条数
 */
function DcUserMetaUpdate($userId=0, $metaKey='', $metaValue=''){
    return \daicuo\User::update_user_meta($userId, $metaKey, $metaValue);
}
/**
 * 通过用户ID与metaKey快速获取一条metaValue
 * @version 1.6.0 首次引入
 * @param string $userId 必需;用户ID;默认：空
 * @param string $metaKey 必需;自定义字段;默认：空
 * @return int $int 影响条数
 */
function DcUserMetaGet($userId=0, $metaKey=''){
    return \daicuo\User::get_user_meta($userId, $metaKey);
}
/**
 * 通过用户ID与metaKey快速获取一条metaValue
 * @version 1.6.0 首次引入
 * @param string $userId 必需;用户ID;默认：空
 * @return array $array keyvalue形式的数组
 */
function DcUserMetaSelect($userId){
    return \daicuo\User::select_user_meta($userId);
}
/************************************用户TOKEN模块*******************************************************/
/**
 * 通过用户名与密码快速获取TOKEN信息、每次成功后会刷新过期时间
 * @version 1.6.0 首次引入
 * @param string $userName 必需;用户名;默认：空
 * @param string $userPass 必需;用户密码;默认：空
 * @return mixed $mixed 登录成功时返回token值与过期时间(array|false)
 */
function DcUserTokenLogin($userName='',$userPass=''){
    return \daicuo\User::token_login($userName,$userPass);
}
/**
 * 通过Token值验证是否正常（是否存在并未过期）
 * @version 1.6.0 首次引入
 * @param string $userToken 必需;用户TOKEN;默认：空
 * @return bool $bool true|false
 */
function DcUserTokenCheck($userToken=''){
    return \daicuo\User::token_check($userToken);
}
/**
 * 通过用户ID生成新的TOKEN
 * @version 1.6.0 首次引入
 * @param int $userId 必需;用户ID;默认：空
 * @return string $string 用户TOKEN
 */
function DcUserTokenCreate($userId=0){
    return \daicuo\User::token_create($userId);
}
/**
 * 通过用户ID设置用户TOKEN过期
 * @version 1.6.0 首次引入
 * @param int $userId 必需;用户ID;默认：空
 * @return string $string 用户TOKEN
 */
function DcUserTokenDelete($userId=0){
    return \daicuo\User::token_delete($userId);
}
/**
 * 通过用户ID修改Token过期时间与Token值
 * @version 1.6.0 首次引入
 * @param int $userId 必需;用户ID;默认：空
 * @param string $userExpire 必需;延迟时长（天）;默认：30
 * @param string $userToken 可选;自定义TOKEN（不修改传入旧的）;默认：空
 * @return mixed $mixed 成功时返回Token值与过期时间(array|false)
 */
function DcUserTokenUpdate($userId=0, $userExpire=30, $userToken=''){
    return \daicuo\User::token_update($userId, $userExpire, $userToken);
}
/**
 * 通过Token值延迟过期时间
 * @version 1.6.0 首次引入
 * @param string $userToken 必需;用户旧TOKEN;默认：空
 * @param string $userExpire 必需;延迟时长（天）;默认：30
 * @return array $array TOKEN值与新的过期时间
 */
function DcUserTokenRefresh($userToken='', $userExpire=30){
    return \daicuo\User::token_refresh($userToken, $userExpire);
}
/**
 * 通过Token请求获取用户信息（Header['HTTP-TOKEN']>Url['token']）
 * @version 1.6.0 首次引入
 * @return array $array 失败时返回游客信息
 */
function DcUserTokenGet(){
    return \daicuo\User::token_current_user();
}
/************************************用户权限模块*******************************************************/
/**
 * 获取系统已配置的角色与权限对应关系
 * @version 1.6.0 首次引入
 * @return array $array 二维数组对应关系
 */
function DcAuthConfig(){
    return \daicuo\Auth::get_config();
}
/**
 * 验证一个用户拥有的所有权限是否在权限节点内
 * @version 1.6.0 首次引入
 * @param string|array $name 必需;需要验证的规则列表,支持逗号分隔的权限规则或索引数组;默认空
 * @param string|array $userRoles 必需;用户的角色名(为用户ID时自动查询);默认空
 * @param string|array $userCaps 可选;用户的权限节点名（可单独设置）;默认空
 * @param string $relation 可选;验证关系(and|or);默认：or
 * @param string $mode 可选;执行验证的模式,可分为url,normal;默认：url
 * @return bool $bool true|false
 */
function DcAuthCheck($name, $userRoles='', $userCaps='', $relation = 'or', $mode = 'url'){
    return \daicuo\Auth::check($name, $userRoles, $userCaps, $relation, $mode);
}