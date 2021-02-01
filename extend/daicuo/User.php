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
     * @return array
     */
    protected static function is_lock($isSet=false)
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
         $max_error = intval(config('common.user_max_error'));//最大错误次数
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
    public static function token_login($user_name, $user_pass)
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
        if( !$result = self::token_update($user['user_id']) ){
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
     * 修改Token
     * @param int $user 用户信息数组
     * @param string $user_expire 延迟时长
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
        $data['user_expire'] = strtotime("+$user_expire days");
        /*
        foreach($user as $key=>$value){
            if( in_array($key, config('custom_fields.user_meta')) ){
                $data[$key] = $value;
            }
        }*/
        $result = self::update_user_by('user_id', $user_id, $data);//可自动更新验证查询缓存
        if( is_null($result) ){
            self::$error = lang('user_update_error');
            return false;
        }
        return $data;
    }

    /**
     * 用户登录
     * @return bool
     */
    public static function login()
    {
        // 是否IP已锁定
        if( self::is_lock() ){
            self::$error = lang('user_locked');
            return false;
        }
        // 接收表单
        $post = input('post.');
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
        $data = self::get_user_by($field, input('user_name/s'), false);
        if(!$data){
            self::is_lock(true);//记录出错次数
            config('daicuo.error', lang('user_name_error'));
            return false;
        }
        if(md5(input('user_pass/s')) != $data['user_pass']){
            self::is_lock(true);//记录出错次数
            config('daicuo.error', lang('user_pass_error'));
            return false;
        }
        if('normal' != $data['user_status']){
            config('daicuo.error', lang('user_status_error'));
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
            config('daicuo.error', lang('cookie').lang('unSupport'));
            return false;
        }
        // 预留钩子
        \think\Hook::listen('user_login_after', $data);
        // 返回结果
        return true;
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
    public function is_logged_in()
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
            if( !empty($user) ){
                if($user_id == $user['user_id'] ){
                    return $user;
                }
            }
            //数据库查询有效用户ID
            $user = self::get_user_by('user_id', $user_id, false);
            if($user){
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
        return $status;
    }
    
    /**
     * 按模块名删除整个模块的用户
     * @param array module 模块名
     * @return array 影响数据
     */
    public static function delete_module($module)
    {
        if($module){
           return self::delete_all(['user_module'=>['eq', $module]]); 
        }
        return 0;
    }
    
    /**
     * 按字段删除一个用户
     * @param string $field 字段条件
     * @param string $value 字段值
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete_user_by($field, $value)
    {
        $value = trim( $value );
        if ( ! $value ) {
            return '0';
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile','user_token']) ){
            return '0';
        }
        if('user_id' == $field){
            if($value < 1){
                return '0';
            }
        }
        $where = array();
        $where[$field] = ['eq', $value];
        return self::delete($where);
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
            //预留钩子user_delete_all_before
            \think\Hook::listen('user_delete_all_before', $user_id);
            $status['user_meta'] = db('userMeta')->where(['user_id'=>['in',$user_id]])->delete();
            $status['user'] = db('user')->where(['user_id'=>['in',$user_id]])->delete();
            //预留钩子user_delete_all_after
            \think\Hook::listen('user_delete_all_after', $user_id, $status);
            //缓存标识清理
            DcCacheTag('common/User/Item', 'clear');
        }
        return $status;
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
     * 通过字段获取用户信息
     * @param string $field 字段条件 
     * @param string $value 字段值
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_user_by($field, $value, $cache=true)
    {
        $value = trim( $value );
        if ( !$value ) {
            self::$error = lang('mustIn');
            return null;
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile','user_token']) ){
            self::$error = lang('mustIn');
            return null;
        }
        $where = array();
        $where[$field] = ['eq', $value];
        $data = self::get($where, 'user_meta', $cache);
        if(!is_null($data)){
            $data = $data->toArray();
            $data_meta = DcManyToData($data, 'user_meta');//将user_meta的信息全并一起返回
            unset($data['user_meta']);
            return array_merge($data, $data_meta);
        }
        self::$error = lang('empty');
        return null;
    }
    
    /**
     * 新增一个用户的meta自定义附加信息
     * @param int $user_id 用户ID
     * @param string $user_meta_key 自定义KEY
     * @param string $user_meta_value 自定义value 支持数组
     * @return obj|null 不为空时返回obj
     */
    public static function save_user_meta($user_id, $user_meta_key, $user_meta_value)
    {
        $data = [];
        $data['user_id'] = $user_id;
        $data['user_meta_key'] = $user_meta_key;
        $data['user_meta_value'] = $user_meta_value;
        return DcDbSave('common/userMeta', $data);
    }
    
    /**
     * 删除一个用户的meta自定义附加信息
     * @param int $where 删除条件
     * @return int 影响数
     */
    public static function delete_user_meta($where)
    {
        return DcDbDelete('common/userMeta', $where);
    }
    
    /**
     * 修改一个用户的meta自定义附加信息 自动清理缓存
     * @param int $user_id 用户ID
     * @param string $user_meta_key 自定义KEY
     * @param string $user_meta_value 自定义value 支持数组
     * @return obj|null 不为空时返回obj
     */
    public static function update_user_meta($user_id, $user_meta_key, $user_meta_value)
    {
        $where = array();
        $where['user_id'] = ['eq', $user_id];
        $where['user_meta_key'] = ['eq', $user_meta_key];
        return DcDbUpdate('common/userMeta', $where, ['user_meta_value'=>$user_meta_value]);
    }
    
    /**
     * 获取一个用户的meta自定义附加信息
     * @param int $user_id 用户ID
     * @param string $user_meta_key 自定义KEY
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @param string $single 关系表达式
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_user_meta($user_id, $user_meta_key, $cache=true, $single='eq')
    {
        $where = array();
        $where['user_id'] = ['eq',$user_id];
        $where['user_meta_key'] = [$single, $user_meta_key];
        $args = [
            'cache'     => $cache,
            'where'     => $where,
            'fetchSql'  => false,
        ];
        $data = DcDbFind('common/userMeta', $args);
        if(!is_null($data)){
            return $data->user_meta_value;
            //return $data->toArray();
        }
        return null;
    }
    
    //获取所有的用户信息（可筛选）
    public static function get_users_metas($args)
    {
    
    }
    
    /**
     * 统计每个角色及网站全部用户数量
     * @param string $strategy
     * @return array 返回每个角色的用户数量，以及所有用户的总数
     */
    public static function count_users($strategy = 'time')
    {
    
    }
    
    /**
     * 创建一个新用户
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return int 添加成功返回自增ID
     */
    public static function save($data, $relation='user_meta')
    {
        //数据验证及格式化数据
        if(!$data = self::data_post($data)){
            return null;
		}
        //钩子传参定义
        $params = array();
        $params['data'] = $data;
        $params['relation'] = $relation;
        $params['result'] = false;
        unset($data);unset($relation);
        //预埋钩子
        \think\Hook::listen('user_save_before', $params);
        //添加数据
        if( false == $params['result'] ){
            $params['result'] = DcDbSave('common/User', $params['data'], $params['relation']);
            if(!$params['result']){
                self::$error = lang('user_create_error');
            }
        }
        //预埋钩子
        \think\Hook::listen('user_save_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件删除一个用户
     * @param array $where 删除条件
     * @param string|array $relation 关联表 
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete($where, $relation='user_meta')
    {
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['relation'] = $relation;
        $params['result'] = 0;
        unset($where);unset($data);unset($relation);
        //预埋钩子
        \think\Hook::listen('user_delete_before', $params);
        //删除数据
        if( 0 == $params['result'] ){
            $params['result'] = DcDbDelete('common/User', $params['where'], $params['relation']);
            if(!$params['result']){
                self::$error = lang('user_delete_error');
            }
        }
        //预埋钩子
        \think\Hook::listen('user_delete_after', $params);
        //返回结果
        return implode(',', $params['result']);
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
        //钩子传参定义
        $params = array();
        $params['where'] = $where;
        $params['data'] = $data;
        $params['relation'] = $relation;
        unset($where);unset($data);unset($relation);
        //预埋钩子
        \think\Hook::listen('user_update_before', $params);
        //修改数据
        if( false == $params['result'] ){
            $params['result'] = DcDbUpdate('common/User', $params['where'], $params['data'], $params['relation']);
            if(!$params['result']){
                self::$error = lang('user_update_error');
            }
        }
        //预埋钩子
        \think\Hook::listen('user_update_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 按条件查询一个用户
     * @param array $where 查询条件（一维数组）
     * @param bool $cache 是否开启缓存功能由后台统一配置
     * @param string|array $with 关联预载入表 
     * @return obj|null 不为空时返回obj
     */
    public static function get($where, $with='user_meta', $cache=true, $view='')
    {
        //钩子传参定义
        $params = array();
        $params['result'] = false;
        $params['args'] = [
            'cache'     => $cache,
            'where'     => $where,
            'with'      => $with,
            'view'      => $view,
            'fetchSql'  => false,
        ];
        //释放变量
        unset($where);unset($relation);unset($cache);unset($view);
        //预埋钩子
        \think\Hook::listen('user_get_before', $params);
        //数据库查询
        if( false == $params['result'] ){
            $params['result'] = DcDbFind('common/User', $params['args']);
            if(!$params['result']){
                self::$error = lang('user_get_error');
            }
        }
        //预埋钩子
        \think\Hook::listen('user_get_after', $params);
        //返回结果
        return $params['result'];
    }
    
    //获取所有的用户信息（可筛选）
    public static function all($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //钩子传参定义
        $params = array();
        $params['result'] = false;
        $params['args'] = array_merge([
            'cache'     => true,
            'field'     => '*',//term.*,termMuch.term_much_id as ids
            'fetchSql'  => false,
            'sort'      => 'term_id',
            'order'     => 'asc',
            'paginate'  => '',
            'where'     => '',
            'with'      => 'userMeta',
            'view'      => '',
        ], $args);unset($args);//旧参数
        //查询分类数据前的钩子
        \think\Hook::listen('user_all_before', $params);
        //数据库查询
        if( false == $params['result'] ){
            $params['result'] = DcDbSelect('common/User', $params['args']);
            if(!$params['result']){
                self::$error = lang('user_all_error');
            }
        }
        //查询数据后的钩子
        \think\Hook::listen('user_all_after', $params);
        //返回结果
        return $params['result'];
    }
    
    /**
     * 转换post数据
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