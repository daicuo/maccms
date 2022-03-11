<?php
namespace daicuo;

class Auth
{
    /**
     * 返回系统所有权限配置(角色名=>权限节点)
     * @return array 普通数组的形式
     */
    public static function get_config()
    {
        $roles = DcCache('auth_all');
        if(!$roles){
            $args = array();
            $args['cache']    = false;
            $args['field']    = 'op_id,op_name,op_value';
            $args['sort']     = 'op_id';
            $args['order']    = 'asc';
            $args['status']   = ['eq','normal'];
            $args['controll'] = ['eq','auth'];
            //角色名分组
            $roles = [];
            foreach(model('common/Config','loglic')->select($args) as $key=>$value){
                if($value['op_name'] && $value['op_value']){
                    $roles[$value['op_name']][] = $value['op_value'];
                }
            }
            //角色节点排重
            foreach($roles as $key=>$value){
                $roles[$key] = array_unique($value);
            }
            //缓存用户组
            if( !is_null($roles) ){
                DcCache('auth_all', $roles, 0);
            }
        }
        if($roles){
            return DcArrayArgs($roles, config('user_roles'));
        }
        return config('user_roles');
    }
    
    /**
     * 返回系统所有已注册的角色名(用户组名)
     * @return array 普通数组的形式
     */
    public static function get_roles()
    {
        $roles = self::get_config();
        
        unset($roles['caps']);//临时动态扩展节点不返回
        
        $roles = array_keys($roles);
        
        return $roles;
    }
    
    /**
     * 返回系统所有已注册的权限节点名
     * @return array 普通数组的形式
     */
    public static function get_caps()
    {
        $caps = array();
        foreach(self::get_config() as $key => $value){
            if(is_array($value)){
                $caps = array_merge($caps, $value);
            }
        }
        return array_unique($caps);
    }
    
    /**
     * 获取一个用户拥有的角色名(用户组)
     * @param string|array $user_roles 用户的权限信息
     * @return array 以普通数组的形式返回用户拥有的角色
     */
    public static function get_user_roles($user_roles)
    {
        if(!$user_roles){
            return [];
        }
        if(is_string($user_roles)){
            $rolesUser = [$user_roles];
        }else{
            $rolesUser = $user_roles;
        }
        return array_intersect(self::get_roles(), $rolesUser);//取交集
    }
    
    /**
     * 获取一个用户拥有的所有权限节点
     * @param string|array $user_roles 用户的角色名/用户组名
     * @param string|array $user_caps 用户的权限节点名（可单独设置）
     * @return array 以普通数组的形式返回用户拥有的权限
     */
    public static function get_user_caps($user_roles=[], $user_caps=[])
    {
        $caps = array();
        if(!$user_roles){
            return $caps;
        }
        if(is_string($user_roles)){
            $user_roles = [$user_roles];
        }
        //系统注册的角色与权限关系列表
        $rolesConfig = self::get_config();
        //用户拥有的角色组（键名与键值交换）
        $rolesUser = array_flip($user_roles);
        //取交集以键名为准
        $rolesArray = array_intersect_key($rolesConfig, $rolesUser);
        //合并的二维转普通数组
        foreach($rolesArray as $key=>$value){
            if(is_array($value)){
                $caps = array_merge($caps, $value);
            }
        }
        //用户拥有的动态权限节点
        if(is_string($user_caps)){
            $user_caps = [$user_caps];
        }
        if($user_caps){
            $caps = array_merge($caps, $user_caps); 
        }
        //去除重复
        return array_unique($caps);
    }
    
    /**
     * 检查权限
     * @param string|array $name 需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param int|array $user_roles 用户ID或者用户拥有的角色组
     * @param int|array $user_capss 用户拥有的单独权限节点
     * @param string $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @param string $mode 执行验证的模式,可分为url,normal
     * @return bool 通过验证返回true;失败返回false
     */
    public static function check($name, $user_roles='', $user_caps='', $relation = 'or', $mode = 'url')
    {
        //权限节点列表
        if(empty($name) && empty($user_roles)){
            return true;
        }
        //用户ID则直接查询
        if( is_numeric($user_roles) ){
            $user = \daicuo\User::get_user_by('user_id',$user_roles);
            $rulelist = self::get_user_caps($user['user_capabilities'], $user['user_caps']);
            unset($user);
        }else{
            $rulelist = self::get_user_caps($user_roles, $user_caps);
        }
        //开始验证
        if (in_array('*', $rulelist)) {
            return true;
        }
        if (is_string($name)) {
            $name = strtolower($name);
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = [$name];
            }
        }
        $list = []; //保存验证通过的规则名
        if ('url' == $mode) {
            $REQUEST = unserialize(strtolower(serialize(request()->param())));
        }
        foreach ($rulelist as $rule) {
            $query = preg_replace('/^.+\?/U', '', $rule);
            if ('url' == $mode && $query != $rule) {
                parse_str($query, $param); //解析规则中的param
                $intersect = array_intersect_assoc($REQUEST, $param);
                $rule = preg_replace('/\?.*$/U', '', $rule);
                if (in_array($rule, $name) && $intersect == $param) {
                    //如果节点相符且url参数满足
                    $list[] = $rule;
                }
            } else {
                if (in_array($rule, $name)) {
                    $list[] = $rule;
                }
            }
        }
        if ('or' == $relation && !empty($list)) {
            return true;
        }
        
        $diff = array_diff($name, $list);
        if ('and' == $relation && empty($diff)) {
            return true;
        }

        return false;
    }
}