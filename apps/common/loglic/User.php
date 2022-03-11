<?php
namespace app\common\loglic;

class User
{
    protected $error = '';
    
    public function getError(){
        return $this->error;
    }
    
    /**
     * 新增或修改一条用户数据（有user_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $post 必需;参考默认字段;默认：空
     * @param string $validateName 可选;验证规则路径;默认：空
     * @param string $validateScene 可选;验证场景;默认：空
     * @param mixed $slugUnique 可选;别名规则，禁用为false;默认：空
     * @return mixed obj|null
     */
    public function write($post=[], $validateName='common/User', $validateScene='empty', $slugUnique=[])
    {
        config('common.validate_name', $validateName);//验证规则
        
        config('common.validate_scene', $validateScene);//验证场景
        
        config('common.where_slug_unique', $slugUnique);//别名唯一值规则
        
        config('custom_fields.user_meta', $this->metaKeys());//所有扩展字段
        //修改
        if($post['user_id']){
            return \daicuo\User::update_id($post['user_id'], $post);
        }
        //新增
        return \daicuo\User::save($post);
    }
    
    /**
     * 按条件删除一条用户数据
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type int $id 可选;配置ID;默认：空
     *     @type string $name 可选;配置名称;默认：空
     *     @type string $slug 可选;配置名称;默认：空
     *     @type string $module 可选;模型名称;默认：空
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed $mixed 查询结果（array|null）
     */
    public function delete($args=[]){
        //动态参数
        $where = DcWhereFilter($args, ['id','name','slug','module','status'], 'eq', 'user_');
        //参数合并
        if($args['where']){
            $where = DcArrayArgs($args['where'], $where);
        }
        return \daicuo\User::delete($where);
    }
    
    /**
     * 按条件获取一个用户数据
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type int $id 可选;用户ID;默认：空
     *     @type mixed $name 可选;用户名称(stirng|array);默认：空
     *     @type mixed $slug 可选;用户别名(stirng|array);默认：空
     *     @type mixed $module 可选;模型名称(stirng|array);默认：空
     *     @type mixed $email 可选;用户邮箱(int|array);默认：空
     *     @type mixed $mobile 可选;用户手机(stirng|array);默认：空
     *     @type mixed $token 可选;用户令牌(string|array);默认：空
     *     @type mixed $meta_key 可选;扩展字段限制条件(string|array);默认：空
     *     @type mixed $meta_value 可选;扩展字段值限制条件(string|array);默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed $mixed 查询结果（array|null）
     */
    public function get($args=[]){
        //where动态字段参数
        $where = DcWhereFilter($args, ['id','name','slug','module','status','email','mobile','token','meta_key','meta_value'], 'eq', 'user_');
        //where动态数组参数
        if($args['where']){
            $args['where'] = DcArrayArgs($args['where'], $where);
        }else{
            $args['where'] = $where;
        }
        //返回结果
        return \daicuo\User::meta_attr( \daicuo\User::get( DcArrayEmpty($args) ) );
    }
    
    /**
     * 获取多条用户数据
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：false
     *     @type int $limit 可选;分页大小;默认：0
     *     @type int $page 可选;当前分页;默认：0
     *     @type string $field 可选;查询字段;默认：*
     *     @type string $result 可选;返回结果类型(array|tree|obj);默认：array
     *     @type string $sort 可选;排序字段名(user_id|user_views|user|hits|user_meta_key|user_meta|value|meta_value_num);默认：op_order
     *     @type string $order 可选;排序方式(asc|desc);默认：asc
     *     @type string $search 可选;搜索关键词（名称与别名）;默认：空
     *     @type mixed $status 可选;显示状态（normal|hidden）;默认：空
     *     @type mixed $module 可选;来源模块(stirng|array);默认：空
     *     @type mixed $controll 可选;来源控制器(stirng|array);默认：空
     *     @type mixed $action 可选;来源操作(stirng|array);默认：空
     *     @type mixed $id 可选;用户ID(int|array);默认：空
     *     @type mixed $slug 可选;分类别名(stirng|array);默认：空
     *     @type mixed $name 可选;用户名(stirng|array);默认：空
     *     @type mixed $nice_name 可选;呢称(stirng|array);默认：空
     *     @type mixed $email 可选;用户邮箱(stirng|array);默认：空
     *     @type mixed $mobile 可选;用户手机(stirng|array);默认：空
     *     @type mixed $creatime_time 可选;创建时间(int|array);默认：空
     *     @type mixed $update_time 可选;修改时间(int|array);默认：空
     *     @type mixed $creatime_ip 可选;注册时IP(stirng|array);默认：空
     *     @type mixed $update_ip 可选;最后登录IP(string|array);默认：空
     *     @type mixed $views 可选;人气值(int|array);默认：空
     *     @type mixed $hits 可选;点击数(int|array);默认：空
     * }
     * @return mixed $mixed obj|array|null 查询结果
     */
    function select($args=[]){
        //基础定义
        $defaults = array();
        $defaults['cache']    = true;
        $defaults['group']    = '';
        $defaults['with']     = 'user_meta';
        $defaults['join']     = [];
        $defaults['view']     = [];
        $defaults['where']    = [];
        $defaults['paginate'] = [];
        //分页处理
        if(!$args['paginate']){
            if($defaults['paginate'] = DcPageFilter($args)){
                unset($args['limit']);
                unset($args['page']);
            }
        }
        //多条件采用JOIN查询
        if($args['meta_query']){
            $defaults['field'] = 'user.*';
            $defaults['alias'] = 'user';
            $defaults['group'] = 'user.user_id';
            //where动态参数
            foreach(DcWhereFilter($args, ['id','name','slug','email','mobile','token','status','module','controll','action'], 'eq', 'user.user_') as $key=>$where){
                array_push($defaults['where'],[$key=>$where]);
            }
            //where动态拼装自定义字段多条件与JOIN
            foreach($args['meta_query'] as $key=>$where){
                //join参数拼装
                array_push($defaults['join'],['user_meta t'.$key, 't'.$key.'.user_id = user.user_id']);
                //where参数拼装
                $whereSon = [];
                if( isset($where['key']) ){
                    $whereSon['t'.$key.'.user_meta_key']  = DcWhereValue($where['key'],'eq');
                }
                if( isset($where['value']) ){
                    $whereSon['t'.$key.'.user_meta_value'] = DcWhereValue($where['value'],'eq');
                }
                if($whereSon){
                    array_push($defaults['where'], $whereSon);
                }
            }
            //where搜索参数
            if($args['search']){
                array_push($defaults['where'],['user.user_name|user.user_slug|user.user_nice_name|user.user_email|user.user_mobile'=>['like','%'.DcHtml($args['search']).'%']]);
                unset($args['search']);
            }
            //where参数合并
            if($args['where']){
                foreach($args['where'] as $argsKey=>$argsWhere){
                    array_push($defaults['where'], [$argsKey=>$argsWhere]);
                }
                unset($args['where']);
            }
            //排序处理
            if($args['sort'] == 'meta_value_num'){
                $args['sort'] = '';
                $args['group'] = 'user.user_id,t0.user_meta_value';
                $args['orderRaw'] = 't0.user_meta_value+0 '.$args['order'];
            }
            //清除内存
            unset($args['meta_query']);
            //返回结果
            return \daicuo\User::result(\daicuo\User::all(DcArrayArgs($args, $defaults)), DcEmpty($args['result'],'array'));
        }
        //视图查询
        if($args['meta_key'] || $args['meta_value']){
            $defaults['field'] = 'user.*';
            $defaults['group'] = 'user.user_id';
            $defaults['view']  = [
                ['user', '*'],
                ['user_meta', NULL, 'user_meta.user_id=user.user_id'],
            ];
        }
        //where动态参数
        $defaults['where'] = DcWhereFilter($args, ['id','name','slug','email','mobile','token','status','module','controll','action','meta_key','meta_value'], 'eq', 'user_');
        //where搜索参数
        if($args['search']){
            $defaults['where'] = DcArrayArgs(['user_name|user_slug|user_nice_name|user_email|user_mobile'=>['like','%'.DcHtml($args['search']).'%']],$defaults['where']);
            unset($args['search']);
        }
        //where参数合并
        if($args['where']){
            $defaults['where'] = DcArrayArgs($args['where'], $defaults['where']);
            unset($args['where']);
        }
        //排序处理
        if($args['sort'] == 'meta_value_num'){
            $args['sort'] = '';
            $args['group'] = 'user.user_id,user_meta.user_meta_value';
            $args['orderRaw'] = 'user_meta_value+0 '.$args['order'];
        }
        //返回结果
        return \daicuo\User::result(\daicuo\User::all(DcArrayArgs($args, $defaults)), DcEmpty($args['result'],'array'));
    }
    
    /**
     * 按ID删除多个用户数据
     * @version 1.8.10 首次引入
     * @param array $post 必需;数组格式;默认:空
     * @param string $parentName 可选;父级名称;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function deleteIds($ids=[])
    {
        return \daicuo\User::delete_ids($ids);
    }
    
    /**
     * 按ID修改多个用户状态
     * @version 1.8.10 首次引入
     * @param array $ids 必须;ID列表;默认:空
     * @return int 影响条数
     */
    public function status($ids=[],$value)
    {
        $data = [];
        $data['user_status'] = $value;
        return dbUpdate('common/User',['user_id'=>['in',$ids]], $data);
    }
    
    /**
     * 按ID快速获取一个用户数据
     * @version 1.8.10 首次引入
     * @param int $id 必须;ID值;默认:空
     * @param bool $cache 可选;是否缓存;默认:false
     * @return array 数组形式
     */
    public function getId($id=[], $cache=false)
    {
        return \daicuo\User::get_id($id, $cache);
    }
    
    /**
     * 只获取模块的所有动态扩展字段KEY
     * @version 1.8.10 首次引入
     * @param string $module 必需;应用名；默认:common
     * @param string $controll 可选;控制器；默认:category
     * @param string $action 可选;操作名；默认:system
     * @return array 二维数组
     */
    public function metaKeys()
    {
        //初始扩展字段名称
        $keys = array_keys(config('custom_fields.user_meta'));
        //动态扩展字段名称
        $plus = model('common/Field','loglic')->forms(['controll'=>'user'],'keys');
        //返回列表
        return array_unique(DcArrayArgs($keys,$plus));
    }
    
    /**
     * 获取模块的所有动态扩展字段列表
     * @version 1.8.10 首次引入
     * @param string $module 必需;应用名；默认:common
     * @param string $controll 可选;控制器；默认:category
     * @param string $action 可选;操作名；默认:system
     * @return array 二维数组
     */
    public function metaList()
    {
        $customs = model('common/Field','loglic')->forms(['controll'=>'user']);
        return DcArrayArgs($customs, config('custom_fields.user_meta'));
    }
}