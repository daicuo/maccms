<?php
namespace daicuo;

class User
{
    // 错误信息
    protected static $error = 'error';
    
    // 当前用户信息
    protected static $currentUser = '';
    
    /**
     * 获取错误信息
     * @return mixed
     */
    public static function getError()
    {
        return self::$error;
    }
    
    /**
     * 检测与设置暴力破解的IP是否应锁定(检测是否因指定时间内错误数过多超出设置值而应锁定IP 主要用于暴力登录与暴力token)
     * @param bool $isSet 是否记录此次出错操作
     * @return bool $bool true|false
     */
    public static function is_lock($isSet=false)
    {
        // 检测开关 时长不设置或为0则不检测
        if(!config('common.user_max_expire') ){
            return false;
        }
        // 白名单IP用户
        if(config('common.user_force_white')){
           if( in_array(request()->ip(), explode(',',config('common.user_force_white')) ) ){
             return false;
           }
        }
        // 定义参数
        $cache_name  = md5('lock'.request()->ip());//UID_IP
        $cache_value = intval(cache($cache_name));//从缓存获取已记录的出错次数
        $max_expire  = intval(config('common.user_max_expire'));//检测时长
        $max_error   = intval(config('common.user_max_error'));//最大错误次数
        // 是否增加出错次数+1
        if($isSet){
            return cache($cache_name, $cache_value+1, $max_expire);
        }
        // 检测是否超出最大错误阀值
        if($cache_value > $max_error){
            return true;
        }
        return false;
    }
    
    /**
     * Token信息
     * @return array
     */
    public static function token_login($user_name='', $user_pass='', $user_expire=30)
    {
        if(!$user_name || !$user_pass){
            self::$error = lang('mustIn');
            return false;
        }
        if( self::is_lock() ){
            self::$error = lang('user_locked');
            return false;
        }
        $user = self::get_user_by('user_name', $user_name);
        if(!$user){
            self::is_lock(true);
            self::$error = lang('user_name_error');
            return false;
        }
        if(md5($user_pass) != $user['user_pass']){
            self::$error = lang('user_pass_error');
            return false;
        }
        if('normal' != $user['user_status']){
            self::$error = lang('user_status_error');
            return false;
        }
        //更新TOKEN
        if( !$result = self::token_update($user['user_id'],$user_expire) ){
            return false;
        }
        //返回登录结果
        return $result;
    }
    
    /**
     * 通过Token获取用户信息
     * @return array
     */
    public static function token_current_user()
    {
        if ( self::token_request() == false ){
            self::set_current_user(0);
        }
        return self::$currentUser;
    }
    
    /**
     * Token请求
     * @param array $user 用户信息数组
     * @return obj|null
     */
    public static function token_request()
    {
        $user_token = DcHtml(request()->header('HTTP-TOKEN'));
        if(!$user_token){
            $user_token = input('request.token/s');
        }
        if(!$user_token){
            self::$error = lang('user_token_none');
            self::set_current_user(0);
            return false;
        }
        $user_token = htmlspecialchars(strip_tags($user_token));
        return self::token_check($user_token);
    }
    
    /**
     * Token验证
     * @param array $user 用户信息数组
     * @return obj|null
     */
    public static function token_check($user_token)
    {
        if( self::is_lock() ){
            self::$error = lang('user_locked');
            return false;
        }
        //查询TOKEN
        $user = self::get_user_by('user_token', $user_token);
        if(!$user){
            self::is_lock(true);//记录错误次数
            self::$error = lang('user_token_error');//设置错误信息
            return false;
        }
        if (time() - $user['user_expire'] > 0) {
            self::$error = lang('user_expire_error');
            return false;//token长时间未使用而过期，需重新登陆
        }
        //设置当前用户信息
        self::$currentUser = $user;
        //返回验证结果
        return true;
    }
    
    /**
     * 生成Token
     * @param int $user_id 用户ID
     * @return string
     */
    public static function token_create($user_id=0)
    {
        return md5(uniqid(microtime(true).$user_id.config('common.site_secret'), true));
    }
    
    /**
     * 生成TOKEN过期时长
     * @version 1.6.9
     * @param string $user_expire 过期时间多少天后
     * @return string $string linux时间戳
     */
    public static function token_expire($user_expire=30)
    {
        $user_expire = DcEmpty($user_expire,30);
        
        return strtotime("+$user_expire days");
    }
    
    /**
     * 删除Token
     * @param int $$user_id 用户ID
     * @return bool
     */
    public static function token_delete($user_id=0)
    {
        $result = self::update_user_meta($user_id, 'user_expire', 0);//设置过期时间为0
        if($result){
            DcCacheTag('user_id_'.$user_id, 'clear');//清空token_check使用get_user_by的查询缓存标签
            return true;
        }
        return false;
    }
    
    /**
     * 通过用户ID修改Token与过期时间
     * @param int $user 用户信息数组
     * @param string $user_expire 延迟过期时长
     * @param string $user_token 用户旧的token
     * @return false|array 失败时返回false
     */
    public static function token_update($user_id=0, $user_expire=30, $user_token='')
    {
        $data = array();
        if(!$user_token){
            $data['user_token'] = self::token_create($user_id);
        }else{
            $data['user_token'] = $user_token;
        }
        $data['user_expire'] = self::token_expire($user_expire);
        //可自动更新验证查询缓存
        $result = self::update_user_by('user_id', $user_id, $data);
        if( is_null($result) ){
            self::$error = lang('user_update_error');
            return false;
        }
        return $data;
    }
    
    /**
     * 通过Token值延迟过期时间
     * @param string $user_token 用户TOKEN值
     * @param int $user_expire 待增加时长天数
     * @return array $array TOKEN值与新的过期时间
     */
    public static function token_refresh($user_token='',$user_expire=30)
    {
        if(!$user_token || !$user_expire){
            self::$error = lang('mustIn');
            return false;
        }
        //更新数据
        $data = [];
        $data['user_expire'] = self::token_expire($user_expire);
        //可自动更新验证查询缓存
        $result = self::update_user_by('user_token', $user_token, $data);
        if( is_null($result) ){
            self::$error = lang('user_update_error');
            return false;
        }
        $data['user_token'] = $user_token;
        return $data;
    }
    
    /**
     * 用户注册
     * @return array
     */
    public static function register($data=[])
    {
        //注册信息
        $post = array();
        $post['user_name']         = $data['user_name'];
        $post['user_mobile']       = $data['user_mobile'];
        $post['user_email']        = $data['user_email'];
        $post['user_pass']         = $data['user_pass'];
        $post['user_pass_confirm'] = $data['user_pass_confirm'];
        $post['user_nice_name']    = uniqid();
        $post['user_slug']         = '';
        $post['user_create_time']  = '';
        $post['user_update_time']  = '';
        $post['user_token']        = self::token_create(0);
        $post['user_expire']       = self::token_expire(config('common.token_expire'));
        $post['user_capabilities'] = ['subscriber'];
        
        //预留钩子
        \think\Hook::listen('user_register_before', $post);
        
        //写入数据
        $userId = self::save($post);
        
        //返回注册用户信息
        $user = self::set_current_user($userId);
        
        // 预留钩子
        \think\Hook::listen('user_register_after', $user);
        
        // 返回结果
        return $user;
    }

    /**
     * 用户登录
     * @return bool
     */
    public static function login($post=[])
    {
        // 是否IP已锁定
        if( self::is_lock() ){
            self::$error = lang('user_locked');
            return false;
        }
        // 是否接收表单
        if(!$post){
            self::$error = lang('empty');
            return false;
        }
        //预留钩子
        \think\Hook::listen('user_login_before', $post);
        // 查询字段
        if( is_email($post['user_name']) ){
            $field = 'user_email';
        }elseif( is_mobile($post['user_name']) ){
            $field = 'user_mobile';
        }else{
            $field = 'user_name';
        }
        // 查询数据库
        $data = self::get_user_by($field, $post['user_name'], false);
        if(!$data){
            self::$error = lang('user_name_error');
            self::is_lock(true);//记录出错次数
            return false;
        }
        if(md5($post['user_pass']) != $data['user_pass']){
            self::$error = lang('user_pass_error');
            self::is_lock(true);//记录出错次数
            return false;
        }
        if('normal' != $data['user_status']){
            self::$error = lang('user_status_error');
            return false;
        }
        // 重置当前用户
        self::$currentUser = $data;
        // 写入cookie
        if($post['user_expire']){
            $cookie_expire = intval(config('common.user_expire'));
        }else{
            $cookie_expire = 0;
        }
        if( self::set_auth_cookie($data['user_id'],$cookie_expire) == false ){
            self::$error = lang('cookie').lang('unSupport');
            return false;
        }
        // 预留钩子
        \think\Hook::listen('user_login_after', $data);
        // 返回结果
        return $data;
    }
    
    /**
    * 用户退出
    * @return mixed
    */
    public static function logout()
    {
        self::clear_auth_cookie();
        self::clear_auth_session();
    }
    
    /**
     * 判断当前用户是否登录
     * @return bool
     */
    public static function is_logged_in()
    {
        if(self::validate_auth_cookie() > 0){
            return true;
        }
        return false;
    }
    
    /**
     * 返回当前登录用户ID
     * @return int 用户ID
     */
    public static function get_current_user_id()
    {
        return self::validate_auth_cookie();
    }
    
    /**
     * 获取当前登录的用户信息
     * @return array
     */
    public static function get_current_user()
    {
        self::set_current_user( self::validate_auth_cookie() );
        return self::$currentUser;
    }
    
    /**
     * 设置当前登录的用户信息 但不会登录该用户
     * @return array
     */
    public static function set_current_user($user_id = 0)
    {
        //有效用户查库
        if($user_id > 0){
            //是否已经有当前用户信息
            $user = self::$currentUser;
            if($user_id == $user['user_id'] ){
                return $user;
            }
            //数据库查询有效用户ID
            $user = self::get_user_by('user_id', $user_id, false);
            if($user){
                $user['user_caps'] = explode(chr(10),$user['user_caps']);
                self::$currentUser = $user;
                return $user;
            }
        }
        //默认用户
        $user = array();
        $user['user_id'] = 0;
        $user['user_name'] = 'guest';
        $user['user_nick_name'] = lang('guest');
        $user['user_capabilities'] = ['guest'];
        self::$currentUser = $user;
        return $user;
    }
    
    /**
     * 设置当前用户登录的cookie后才会登录
     * @param int $user_id 用户ID
     * @param int $expire 过期时间(0=关闭浏览器失效)
     * @return bool
     */
    public static function set_auth_cookie($user_id, $expire = 0)
    {
        $user = self::$currentUser;
        
        if($user_id == $user['user_id']){
            // 服务端密钥
            $user_secert = self::session_hash($user['user_pass']);
            // 客户端密钥
            $user_cookie = self::cookie_hash($user_secert);
            // 写入COOKIE
            cookie('logged_in', $user['user_id'].'%'.$user_cookie, $expire);
            // 返回结果
            return true;
        }
        
        return false;
    }
    
    /**
     * 通过COOKIE信息验证当前用户是否登录
     * @return int 用户ID
     */
    public static function validate_auth_cookie()
    {
        
        list($user_id, $user_cookie) = explode('%', input('cookie.logged_in') );//分割userId与Hash
        
        $user_id = intval($user_id);//强制整数
        
        if($user_id < 1){
            return 0;
        }
        
        // 获取服务端密钥
        $user_secert = input('session.user_secert/s');
        // 服务端密钥已过期（Session过期）
        if(!$user_secert){
            // 数据库获取
            $user = self::get_user_by('user_id', $user_id, false);
            if(!$user){
                self::clear_auth_cookie();
                return 0;
            }
            // 设置当前用户
            self::$currentUser = $user;
            // 重新生成服务端密钥
            $user_secert = self::session_hash($user['user_pass']);
            // 缓存服务端密钥（Session保存）
            session('user_secert', $user_secert);
        }

        // 验证密钥
        if( $user_cookie != self::cookie_hash($user_secert) ){
            self::set_current_user(0);//设置为游客
            self::logout();//清除COOKIE,SESSION
            return 0;
        }
        
        // 返回已登录ID
        return $user_id;
    }
    
    /**
     * 生成加密的SESSION信息（服务端）
     * @param string $user_pass 用户密码
     * @return
     */
    private static function session_hash($user_pass='daicuo'){
        return md5(config('common.site_secret').$user_pass);
    }
    
    /**
     * 生成加密的COOKIE信息(客户端)
     * @param string $user_secert 服务端密钥
     * @return
     */
    private static function cookie_hash($user_secert='daicuo'){
        $cookie_hash = [];
        $cookie_hash[0] = request()->ip();
        $cookie_hash[1] = request()->header('user-agent');
        $cookie_hash[2] = $user_secert;
        return sha1(implode('',$cookie_hash));
    }
    
    /**
     * 清除客户端登录COOKIE
     * @return 
     */
    public static function clear_auth_cookie()
    {
        cookie('logged_in', null);
    }
    
    /**
     * 清除服务端验证SESSION
     * @return
     */
    public static function clear_auth_session()
    {
        session('user_secert', null);
    }
    
    /********************************************************************************************/
    
    /**
     * 批量增加用户
     * @param array $list 写入数据（二维数组） 
     * @return null|obj 添加成功返回自增ID数据集
     */
    public static function save_all($list)
    {
        //关联新增只有循环操作
        foreach($list as $key=>$data){
            $status[$key] = self::save($data);
        }
        //缓存标识清理
        DcCacheTag('common/User/Item', 'clear');
        //缓回结果
        return $status;
    }
    
    /**
     * 按userId快速删除一条用户数据
     * @param int $id 必需;ID值;默认：空
     * @return bool $bool true|false
     */
    public static function delete_id($id='')
    {
        return self::delete_user_by('user_id',$id);
    }
    
    /**
     * 按userId快速删除多条用户数据
     * @param mixed $ids 必需;ID值,多个用逗号分隔(int|string|array);默认：空
     * @return array $array 多条删除记录结果
     */
    public static function delete_ids($ids='')
    {
        $result = [];
        if( is_string($ids) ){
            $ids = explode(',',$ids);
        }
        foreach($ids as $key=>$value){
            array_push($result, self::delete_user_by('user_id',$value));
        }
        return $result;
    }
    
    /**
     * 按模块名删除整个模块的用户
     * @param array $module 模块名
     * @return array $array 影响数据的条数
     */
    public static function delete_module($module)
    {
        if($module){
           return self::delete_all(['user_module'=>['eq', $module]]); 
        }
        return null;
    }
    
    /**
     * 按字段删除一个用户
     * @param string $field 字段条件
     * @param string $value 字段值
     * @return bool $bool true|false
     */
    public static function delete_user_by($field, $value)
    {
        $value = trim($value);
        if ( !$value) {
            return false;
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile','user_token']) ){
            return false;
        }
        if('user_id' == $field){
            if($value < 1){
                return false;
            }
        }
        $where = array();
        $where[$field] = ['eq', $value];
        if( self::delete($where,'user_meta') ){
            return true;
        }
        return false;
    }
    
    /**
     * 批量删除用户数据
     * @param array $where 查询条件
     * @return array 影响数据的条数
     */
    public static function delete_all($where)
    {
        $status = ['user'=>0,'user_meta'=>0];
        $user_id = db('user')->where($where)->column('user_id');
        if($user_id){
            //先删除meta
            $status['user_meta'] = db('userMeta')->where(['user_id'=>['in',$user_id]])->delete();
            //后删除基础
            $status['user'] = db('user')->where(['user_id'=>['in',$user_id]])->delete();
            //缓存标识清理
            DcCacheTag('common/User/Item', 'clear');
        }
        return $status;
    }
    
    /**
     * 按userId快速修改一条用户数据
     * @param int $id 必需;ID值;默认：空
     * @param array $idata 必需;待更新的数据;默认：空
     * @return obj|null 不为空时返回obj
     */
    public static function update_id($id='', $data)
    {
        return self::update_user_by('user_id', $id, $data);
    }
    
    /**
     * 按字段修改一个用户
     * @param string $field 字段条件
     * @param string $value 字段值
     * @param array $data 写入数据（一维数组） 
     * @return obj|null 不为空时返回obj
     */
    public static function update_user_by($field, $value, $data)
    {
        $value = trim( $value );
        if ( ! $value ) {
            return null;
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile','user_token']) ){
            return null;
        }
        if('user_id' == $field) {
            if($value < 1){
                return null;
            }
        }
        $where = array();
        $where[$field] = ['eq', $value];
        return self::update($where, $data);
    }
    
    /**
     * 通过ID获取用户信息
     * @param string $value 字段值 
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return mixed $mixed array|null
     */
    public static function get_id($value, $cache=true)
    {
        return self::get_user_by('user_id', $value, $cache);
    }
    
    /**
     * 通过字段获取用户信息
     * @param string $field 字段条件 
     * @param string $value 字段值
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_user_by($field, $value, $cache=true)
    {
        $value = trim($value);
        if ( !$value ) {
            self::$error = lang('mustIn');
            return null;
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile','user_token']) ){
            self::$error = lang('mustIn');
            return null;
        }
        //获取数据
        $data = self::get([
            'cache' => $cache,
            'field' => '*',
            'where' => [$field=>['eq',$value]],
            'with'  => 'user_meta',
            'view'  => [],
        ]);
        //获取器修改
        $data = self::meta_attr($data);
        //返回结果
        if(is_null($data)){
            self::$error = lang('empty');
            return null;
        }
        return $data;
    }
    
    /**
     * 按用户ID新增一条自定义META
     * @param int $user_id 用户ID
     * @param string $user_meta_key 自定义KEY
     * @param string $user_meta_value 自定义value 支持数组
     * @return int 影响数
     */
    public static function save_user_meta($user_id, $user_meta_key, $user_meta_value)
    {
        if(!$user_id || !$user_meta_key || !$user_meta_value){
            return null;
        }
        $data = [];
        $data['user_id'] = $user_id;
        $data['user_meta_key'] = $user_meta_key;
        $data['user_meta_value'] = $user_meta_value;
        return DcCacheResult(dbInsert('common/userMeta', $data), 'user_id_'.$user_id, 'tag');
    }
    
    /**
     * 按用户ID与metaKey删除自定义META(支持多条)
     * @param int $user_id 用户ID
     * @param string $user_meta_key 自定义KEY
     * @return int 影响数
     */
    public static function delete_user_meta($user_id, $user_meta_key)
    {
        if(!$user_id){
            return 0;
        }
        $where = array();
        
        $where['user_id'] = ['eq', $user_id];
        
        if($user_meta_key){
            $where['user_meta_key'] = ['eq', $user_meta_key];
        }
        return DcCacheResult(dbDelete('common/userMeta',$where), 'user_id_'.$user_id, 'tag');
    }
    
    /**
     * 按用户ID与metaKey修改一条自定义META
     * @param int $user_id 用户ID
     * @param string $user_meta_key 自定义KEY
     * @param string $user_meta_value 自定义value 支持数组
     * @return obj|null 不为空时返回obj
     */
    public static function update_user_meta($user_id, $user_meta_key, $user_meta_value)
    {
        if(!$user_id || !$user_meta_key || !$user_meta_value){
            return null;
        }
        $where = array();
        $where['user_id'] = ['eq', $user_id];
        $where['user_meta_key'] = ['eq', $user_meta_key];
        return DcCacheResult(dbUpdate('common/userMeta', $where, ['user_meta_value'=>$user_meta_value]), 'user_id_'.$user_id, 'tag');
    }
    
    /**
     * 按用户ID与metaKey获取一条META自定义附加信息
     * @param int $user_id 用户ID
     * @param string $user_meta_key 自定义KEY
     * @param string $single 关系表达式
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_user_meta($user_id, $user_meta_key, $single='eq')
    {
        if(!$user_id || !$user_meta_key){
            return null;
        }
        $where = array();
        $where['user_id'] = ['eq',$user_id];
        $where['user_meta_key'] = [$single, $user_meta_key];
        return dbFindValue('common/userMeta', $where, 'user_meta_value');
    }
    
    /**
     * 按用户ID获取所有META自定义附加信息
     * @param int $user_id 用户ID
     * @return array|null 不为空时返回修改后的数据
     */
    public static function select_user_meta($user_id)
    {
        if(!$user_id){
            return null;
        }
        $result = [];
        $list = DcArrayResult( dbSelect('common/userMeta',['user_id'=>['eq',$user_id]]) );
        foreach($list as $key=>$value){
            $result[$value['user_meta_key']] = $value['user_meta_value'];
        }
        return $result;
    }
    
    /**
     * 创建一个新用户
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return int 添加成功返回自增ID
     */
    public static function save($data, $relation='user_meta')
    {
        //删除主键
        unset($data['user_id']);
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //返回结果
        return DcDbSave('common/User', $data, $relation);
    }
    
    /**
     * 按条件删除一个用户
     * @param array $where 删除条件
     * @param string|array $relation 关联表 
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete($where, $relation='user_meta')
    {
        return DcDbDelete('common/User', $where, $relation);
    }
    
    /**
     * 修改一个新用户
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表
     * @return obj|null
     */
    public static function update($where, $data, $relation='user_meta')
    {
        //用户名不可修改
        unset($data['user_name']);
        //没填写新密码时不修改
        if( empty($data['user_pass']) ){
            unset($data['user_pass']);
        }
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //返回结果
        return DcDbUpdate('common/User', $where, $data, $relation);
    }
    
    /**
     * 按条件查询一个用户
     * @param array $args 查询条件（一维数组）
     * @return obj|null 不为空时返回obj
     */
    public static function get($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //初始参数
        $args = DcArrayArgs($args, [
            'cache'     => true,
            'field'     => '*',
            'fetchSql'  => false,
            'where'     => '',
            'with'      => 'user_meta',
            'view'      => [],
        ]);
        //返回结果
        return DcDbFind('common/User', $args);
    }
    
    //获取所有的用户信息（可筛选）
    public static function all($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //初始参数
        $args = DcArrayArgs($args, [
            'cache'     => true,
            'field'     => '*',
            'fetchSql'  => false,
            'sort'      => 'user_id',
            'order'     => 'desc',
            'paginate'  => '',
            'where'     => '',
            'with'      => '',
            'join'      => [],
            'view'      => [
                //['user', '*'],
                //['user_meta', 'user_meta_id', 'user_meta.user_id=user.term_id']
            ],
        ]);
        //返回结果
        return DcDbSelect('common/User', $args);
    }
    
    /**
     * 获取器、整体修改返回的数据类型
     * @param obj $list 必需;数据库查询结果
     * @param string $type 必需;返回类型(array|tree|level|obj);默认：空
     * @return mixed $mixed obj|array|null
     */
    public static function result($list, $type='array')
    {
        if($type == 'array'){
            return self::meta_attr_list($list);
        }
        return $list;
    }
    
    /**
     * 获取器、格式化数据列表为数组
     * @param mixed $data 二维数组或OBJ数据集(array|obj)
     * @return array $array 格式化后的数据
     */
    public static function meta_attr_list($data)
    {
        if( is_null($data) ){
            return null;
        }
        //数据结果
        if( is_object($data) ){
            $data = $data->toArray();
        }
        //是否分页
        if(isset($data['total'])){
            foreach($data['data'] as $key=>$value){
                $data['data'][$key] = self::meta_attr($value);
            }
        }else{
            foreach($data as $key=>$value){
                $data[$key] = self::meta_attr($value);
            }
        }
        return $data;
    }
    
    /**
     * 获取器、格式化扩展表数据
     * @param mixed $data 一维数组或OBJ数据集(array|obj)
     * @return mixed $mixed 格式化后的数据(array|null)
     */
    public static function meta_attr($data)
    {
        
        if( is_null($data) ){
            return null;
        }
        
        if(is_string($data)){
            return $data;
        }
        
        if( is_object($data) ){
            $data = $data->toArray();
        }
        
        //整理数据
        $data = array_merge($data, DcManyToData($data, 'user_meta'));
        //删除旧数据
        unset($data['user_meta']);
        
        //删除关联的原始数据
        unset($data['user_meta_id']);
        unset($data['user_meta_key']);
        unset($data['user_meta_value']);
        //返回数据
        return $data;
    }
    
    /**
     * 修改器、转换post数据
     * @param array $data 表单数据
     * @return array 关联写入数据格式
     */
    public static function data_post($data)
    {
        //表单验证
        $validate = [];
        $validate['data'] = $data;
        $validate['error'] = '';
        $validate['result'] = true;
        //定义钩子验证
        \think\Hook::listen('form_validate', $validate);
        //验证后结果
        if($validate['result'] == false){
            self::$error = $validate['error'];
            return null;
        }
        //销毁变量
        unset($validate);
        //数据整理成关联写入的格式
        //$data = DcDataToMany($data, 'user_capabilities', 'user_meta');
        $data = DcDataToMany($data, DcConfig('custom_fields.user_meta'), 'user_meta');
        return $data;
    }
}