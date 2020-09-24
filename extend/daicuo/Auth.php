<?php
namespace daicuo;

//use think\Request;

class Auth
{
    /**
     * 返回系统所有角色名
     * @return array 普通数组的形式
     */
    public static function get_roles()
    {
        $roles = array();
        foreach(config('user_roles') as $role => $value){
            array_push($roles, $role);
        }
        return $roles;
    }
    
    /**
     * 返回系统所有权限节点
     * @return array 普通数组的形式
     */
    public static function get_caps()
    {
        $caps = array();
        foreach(config('user_roles') as $key => $value){
            if(is_array($value)){
                $caps = array_merge($caps, $value);
            }
        }
        return $caps;
    }
    
    /**
     * 获取一个用户的角色信息
     * @param string|array $capabilities 用户的权限信息
     * @return array 以普通数组的形式返回用户拥有的角色
     */
    public static function get_user_roles($capabilities)
    {
        $roles = array();
        if(empty($capabilities)){
            return $roles;
        }else if(is_string($capabilities)){
            $capabilities = [$capabilities];
        }
        $user_roles = config('user_roles');
        foreach($capabilities as $role){
            if(isset($user_roles[$role])){
                array_push($roles, $role);
            }
        }
        return $roles;
    }
    
    
    /**
     * 获取一个用户拥有的所有权限
     * @param string|array $capabilities 用户的权限信息
     * @return array 以普通数组的形式返回用户拥有的权限
     */
    public static function get_user_caps($capabilities)
    {
        $caps = array();
        if(empty($capabilities)){
            return $caps;
        }else if(is_string($capabilities)){
            $capabilities = [$capabilities];
        }
        $user_roles = config('user_roles');
        foreach($capabilities as $role){
            $cap = $user_roles[$role];
            if(isset($cap)){
                if(is_array($cap)){
                    $caps = array_merge($caps, $cap);
                }else{
                    array_push($caps, $cap);
                }
            }else{
                array_push($caps, $role);
            }
        }
        return $caps;
    }
    
    /**
     * 检查权限
     * @param string|array $name 需要验证的规则列表,支持逗号分隔的权限规则或索引数组
     * @param int $capabilities 认证用户的用户组
     * @param string $relation 如果为 'or' 表示满足任一条规则即通过验证;如果为 'and'则表示需满足所有规则才能通过验证
     * @param string $mode 执行验证的模式,可分为url,normal
     * @return bool 通过验证返回true;失败返回false
     */
    public static function check($name, $capabilities, $relation = 'or', $mode = 'url')
    {
        //if (!$this->config['auth_on']) {
            //return true;
        //}
        $roles = config('user_roles');
        
        $rulelist = self::get_user_caps($capabilities);
        
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