<?php
namespace daicuo;

/**
* 用户管理
*/
class User {
   
    public static $currentUser = '';
    
    /**
     * 用户登录
     * @param  array $config
     * @return object
     */
    public static function login()
    {
        //接收表单数据
        $post = input('post.');
        \think\Hook::listen('user_login_before', $post);
        if( is_email($post['user_name']) ){
            $field = 'user_email';
        }elseif( is_mobile($post['user_name']) ){
            $field = 'user_mobile';
        }else{
            $field = 'user_name';
        }
        //查询数据库
        $data = self::get_user_by($field, input('user_name/s'));
        if(!$data){
            config('daicuo.error', lang('user_name').lang('error'));
            return false;
        }
        if(md5(input('user_pass/s')) != $data['user_pass']){
            config('daicuo.error', lang('user_pass').lang('error'));
            return false;
        }
        if('normal' != $data['user_status']){
            config('daicuo.error', lang('user_status').lang('hidden'));
            return false;
        }
        self::$currentUser = $data;
        if( self::set_auth_cookie($data['user_id']) == false ){
            config('daicuo.error', lang('user_pass').lang('error'));
            return false;
        }
        \think\Hook::listen('user_login_after', $data);
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
    
    //判断当前用户是否登录
    public function is_logged_in(){
        $user = self::get_current_user();
        if($user['user_id'] > 0){
            return true;
        }
        return false;
    }
    
    //返回当前登录用户ID
    public static function get_current_user_id(){
        $user = self::get_current_user();
        return $user['user_id'];
    }
    
    //获取当前登录的用户信息
    public static function get_current_user(){
        $user = self::$currentUser;
        if( !empty($user) ){
            return $user;
        }
        $user_id = self::validate_auth_cookie();
        self::set_current_user($user_id);
        return self::$currentUser;
    }
    
    //设置当前用户为XX用户但不会登录该用户
    public static function set_current_user($user_id = 0){
        //是否已经有当前用户信息
        $user = self::$currentUser;
        if( !empty($user) ){
            if($user_id == $user['user_id'] ){
                return $user;
            }
        }
        //有效用户查库
        if($user_id > 0){
            $data = self::get_user_by('user_id', $user_id);
            if($data){
                self::$currentUser = $data;
                return $data;
            }
        }
        //默认用户
        $user = array();
        $user['user_id'] = 0;
        $user['user_name'] = 'guest';
        $user['user_nicename'] = lang('guest');
        $user['user_capabilities'] = ['guest'];
        self::$currentUser = $user;
        return $user;
    }
    
    //设置登录cookie
    public static function set_auth_cookie($user_id, $remember = false){
        $user = self::$currentUser;
        if($user_id == $user['user_id']){
            $user_token = sha1(config('common.site_secret').$user['user_pass']);
            cookie('logged_in', $user['user_id'].'%'.sha1(request()->ip().request()->header()['user-agent'].$user_token));
            return true;
        }
        return false;
    }
    
    //验证当前用户是否登录
    public static function validate_auth_cookie(){
        //cookie是否存在
        $user_cookie = input('cookie.logged_in');
        list($user_id_cookie, $hash) = explode('%', $user_cookie);
        if(intval($user_id_cookie) < 1){
            return 0;
        }
        //session已过期
        $user_token = input('session.user_token/s');
        if(!$user_token){
            $data = self::get_user_by('user_id', $user_id_cookie);
            if(!$data){
                self::clear_auth_cookie();
                return 0;
            }
            self::$currentUser = $data;
            $user_token = sha1(config('common.site_secret').$data['user_pass']);
            session('user_token', $user_token);
        }
        //验证开始
        if( $hash != sha1(request()->ip().request()->header()['user-agent'].$user_token) ){
            self::clear_auth_cookie();
            return 0;
        }
        //返回登录ID
        return intval($user_id_cookie);
    }
    
    //清除客户端登录COOKIE
    public static function clear_auth_cookie(){
        cookie('logged_in', null);
    }
    
    //清除服务端验证SESSION
    public static function clear_auth_session(){
        session('user_token', null);
    }
 
     /**
     * 通过字段获取用户信息
     * @param string $field 字段条件 
     * @param string $value 字段值
     * @param bool $cache 是否开启缓存功能 由后台统一配置
     * @return array|null 不为空时返回修改后的数据
     */
    public static function get_user_by($field, $value, $cache=true){
        $value = trim( $value );
        if ( ! $value ) {
            return null;
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile']) ){
            return null;
        }
        $where = array();
        $where[$field] = ['eq', $value];
        $data = self::get($where, 'user_meta', $cache);
        if(!is_null($data)){
            $data = $data->toArray();
            //将user_meta的信息全并一起返回
            $data_meta = array();
            foreach($data['user_meta'] as $value){
                $data_meta[$value['user_meta_key']] = $value['user_meta_value'];
            }
            unset($data['user_meta']);
            return array_merge($data, $data_meta);
        }
        return null;
    }
    
    /**
     * 按字段删除一个用户
     * @param string $field 字段条件
     * @param string $value 字段值
     * @return string 返回操作记录数(0|1,1)
     */
    public static function delete_user_by($field, $value){
        $value = trim( $value );
        if ( ! $value ) {
            return '0';
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile']) ){
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
     * 按字段修改一个用户
     * @param string $field 字段条件
     * @param string $value 字段值
     * @param array $data 写入数据（一维数组） 
     * @return obj|null 不为空时返回obj
     */
    public static function update_user_by($field, $value, $data){
        $value = trim( $value );
        if ( ! $value ) {
            return null;
        }
        if( !in_array($field, ['user_id','user_name','user_email','user_mobile']) ){
            return null;
        }
        if('user_id' == $field){
            if($value < 1){
                return null;
            }
        }
        $where = array();
        $where[$field] = ['eq', $value];
        return self::update($where, $data);
    }
    
    /**
     * 创建一个新用户
     * @param array $data 写入数据（一维数组） 
     * @param string|array $relation 关联表 
     * @return int 添加成功返回自增ID
     */
    public static function save($data, $relation='user_meta'){
        //格式化数据
        //$data = DcDataToMany($data, 'user_capabilities', 'user_meta');
        $data = DcDataToMany($data, DcConfig('custom_fields.user_meta'), 'user_meta');
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
    public static function delete($where, $relation='user_meta'){
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
     * @return int 添加成功返回自增ID
     */
    public static function update($where, $data, $relation='user_meta'){
        //用户名不可修改
        unset($data['user_name']);
        //没填写新密码时不修改
        if( empty($data['user_pass']) ){
            unset($data['user_pass']);
        }
        //格式化数据
        //$data = DcDataToMany($data, 'user_capabilities', 'user_meta');
        $data = DcDataToMany($data, DcConfig('custom_fields.user_meta'), 'user_meta');
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
    public static function get($where, $with='user_meta', $cache=true, $view=''){
        //钩子传参定义
        $params = array();
        $params['result'] = false;
        $params['args'] = [
            'cache'     => $cache,
            'where'     => $where,
            'with'      => DcWith($with),
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
        }
        //预埋钩子
        \think\Hook::listen('user_get_after', $params);
        //返回结果
        return $params['result'];
    }
    
    //获取所有的用户信息（可筛选）
    public static function all($args){
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
        }
        //查询数据后的钩子
        \think\Hook::listen('user_all_after', $params);
        //返回结果
        return $params['result'];
    }
    
    //获取一个用户的meta自定义附加信息
    public static function get_user_meta($user_id, $key, $single){

    }
    
    //获取所有的用户信息（可筛选）
    public static function get_users_metas($args){
    
    }
    
    /**
     * 统计每个角色及网站全部用户数量
     * @param string $strategy
     * @return array 返回每个角色的用户数量，以及所有用户的总数
     */
    public static function count_users($strategy = 'time'){
    
    }
    
    /**
     * 批量增加用户
     * @param array $list 写入数据（二维数组） 
     * @return null|obj 添加成功返回自增ID数据集
     */
    public static function save_all($list){
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
    public static function delete_module($module){
        if($module){
           return self::delete_all(['user_module'=>['eq', $module]]); 
        }
        return 0;
    }
    
    /**
     * 批量删除用户数据
     * @param array $where 查询条件
     * @return array 影响数据的条数
     */
    public static function delete_all($where){
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
}