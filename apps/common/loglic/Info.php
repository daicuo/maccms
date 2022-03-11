<?php
namespace app\common\loglic;

class Info
{
    protected $error = '';
    
    //获取错误信息
    public function getError(){
        return $this->error;
    }
    
    /**
     * 新增或修改一条内容数据（有info_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $post 必需;参考默认字段;默认：空
     * @param string $validateName 可选;验证规则路径;默认：空
     * @param string $validateScene 可选;验证场景;默认：空
     * @param mixed $slugUnique 可选;别名规则，禁用为false;默认：空
     * @return mixed obj|null
     */
    public function write($post=[], $validateName='common/Info', $validateScene='save', $slugUnique=[])
    {
        config('common.validate_name', $validateName);//验证规则
        
        config('common.validate_scene', $validateScene);//验证场景
        
        config('common.where_slug_unique', $slugUnique);//别名唯一值规则
        
        config('custom_fields.info_meta', $this->metaKeys($post['info_module'],$post['info_controll']));//所有扩展字段
        //修改
        if($post['info_id']){
            return \daicuo\Info::update_id($post['info_id'], $post);
        }
        //新增
        return \daicuo\Info::save($post);
    }
    
    //按条件删除队列（fori++循环清空）
    public function delete($args=[])
    {
        $where = DcWhereFilter($args, ['id','name','slug','module','controll','action','status','type'], 'eq', 'info_');
        if(!$where){
            return false;
        }
        
        $ids = db('info')->where($where)->limit(500)->column('info_id');
        if(!$ids){
            return false;
        }
        
        db('infoMap')->where(['info_id'=>['in',$ids]])->delete();
            
        db('infoMeta')->where(['info_id'=>['in',$ids]])->delete();

        db('info')->where(['info_id'=>['in',$ids]])->delete();
        
        DcCacheTag('common/Info/Item', 'clear');
        
        return true;
    }
    
    /**
     * 按条件获取一个内容数据
     * @version 1.6.0 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type int $id 可选;内容ID;默认：空
     *     @type mixed $name 可选;内容名称(stirng|array);默认：空
     *     @type mixed $slug 可选;内容别名(stirng|array);默认：空
     *     @type mixed $module 可选;模型名称(stirng|array);默认：空
     *     @type mixed $meta_key 可选;扩展字段限制条件(string|array);默认：空
     *     @type mixed $meta_value 可选;扩展字段值限制条件(string|array);默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed $mixed 查询结果（array|null）
     */
    public function get($args=[]){
        //where动态字段参数
        $where = DcWhereFilter($args, ['id','title','name','slug','excerpt','password','parent','user_id','create_time','update_time','type','mime_type','views','hits','status','module','controll','action'], 'eq', 'info_');
        //where动态数组参数
        if($args['where']){
            $args['where'] = DcArrayArgs($args['where'], $where);
        }else{
            $args['where'] = $where;
        }
        //返回结果
        return \daicuo\Info::meta_attr( \daicuo\Info::get( DcArrayArgs($args) ) );
    }
    
    /**
     * 获取多条内容数据
     * @version 1.6.0 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type int $limit 可选;分页大小;默认：0
     *     @type int $page 可选;当前分页;默认：0
     *     @type string $field 可选;查询字段;默认：*
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type string $sort 可选;排序字段名(info_id|info_order|info_views|info_hits|meta_value_num);默认：info_id
     *     @type string $order 可选;排序方式(asc|desc);默认：asc
     *     @type string $search 可选;搜索关键词（info_name|info_slug|info_excerpt）;默认：空
     *     @type mixed $id 可选;内容ID限制条件(int|array);默认：空
     *     @type mixed $title 可选;标题限制条件(stirng|array);默认：空
     *     @type mixed $name 可选;名称限制条件(stirng|array);默认：空
     *     @type mixed $slug 可选;别名限制条件(stirng|array);默认：空
     *     @type mixde $module 可选;所属模块(stirng|array);默认：空
     *     @type mixde $controll 可选;所属迭制器(stirng|array);默认：空
     *     @type mixde $action 可选;所属操作(stirng|array);默认：空
     *     @type mixed $term_id 可选;分类法ID限制条件(string|array);默认：空
     *     @type array $meta_query 可选;自定义字段(二维数组[key=>['eq','key'],value=>['in','key']]);默认：空
     *     @type string $result 可选;返回结果类型(array|obj);默认：array
     *     @type array $where 可选;自定义高级查询条件;默认：空
     *     @type array $paginate 可选;自定义高级分页参数;默认：空
     * }
     * @return mixed $mixed obj|array|null 查询结果
     */
    public function select($args=[]){
        //基础定义
        $defaults = array();
        $defaults['cache']    = true;
        $defaults['group']    = '';
        $defaults['with']     = 'info_meta,term,user';
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
            $defaults['field'] = 'info.*';
            $defaults['alias'] = 'info';
            $defaults['group'] = 'info.info_id';
            //join固定参数
            array_push($defaults['join'],['term_map','term_map.detail_id = info.info_id']);
            //where动态参数
            foreach(DcWhereFilter($args, ['id','title','name','slug','excerpt','password','parent','user_id','create_time','update_time','type','mime_type','views','hits','status','module','controll','action'], 'eq', 'info.info_') as $key=>$where){
                array_push($defaults['where'],[$key=>$where]);
            }
            //where动态拼装自定义字段多条件与JOIN
            foreach($args['meta_query'] as $key=>$where){
                //join参数拼装
                array_push($defaults['join'],['info_meta t'.$key, 't'.$key.'.info_id = info.info_id']);
                //where参数拼装
                $whereSon = [];
                if( isset($where['key']) ){
                    $whereSon['t'.$key.'.info_meta_key']  = DcWhereValue($where['key'],'eq');
                }
                if( isset($where['value']) ){
                    $whereSon['t'.$key.'.info_meta_value'] = DcWhereValue($where['value'],'eq');
                }
                if($whereSon){
                    array_push($defaults['where'], $whereSon);
                }
                /*array_push($defaults['where'],[
                    't'.$key.'.info_meta_key'   => [DcEmpty($where['compare'],'eq'), $where['key']],
                    't'.$key.'.info_meta_value' => [DcEmpty($where['compare'],'eq'), $where['value']],
                ]);*/
            }
            //where搜索参数
            if($args['search']){
                array_push($defaults['where'],['info.info_title|info.info_name|info.info_slug|info.info_excerpt'=>['like','%'.DcHtml($args['search']).'%']]);
                unset($args['search']);
            }
            //where分类法参数
            if( $args['term_id'] ){
                array_push($defaults['where'],['term_map.term_id' => DcWhereValue($args['term_id'],'in')]);
                unset($args['much_id']);
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
                $args['group'] = 'info.info_id,t0.info_meta_value';//mysql高版本兼容
                $args['orderRaw'] = 't0.info_meta_value+0 '.$args['order'];
            }
            //清理内存
            unset($args['meta_query']);
            //返回结果
            return \daicuo\Info::result(\daicuo\Info::all(DcArrayArgs($args, $defaults)), DcEmpty($args['result'],'array'));
        }
        //是否视图查询
        if($args['meta_key'] || $args['meta_value'] || $args['term_id']){
            //重置默认条件
            $defaults['field'] = 'info.*';
            $defaults['group'] = 'info.info_id';
            $defaults['view']  = [
                ['info', '*'],
                ['info_meta', NULL, 'info_meta.info_id=info.info_id']
            ];
            //队列ID存在时还需关联关系表
            if($args['term_id']){
                array_push($defaults['view'], ['term_map' , NULL, 'term_map.detail_id=info.info_id'] );
            }
            //自定义字段排序处理
            if($args['sort'] == 'meta_value_num'){
                $args['sort'] = '';
                $args['group'] = 'info.info_id,info_meta.info_meta_value';//mysql高版本兼容
                $args['orderRaw'] = 'info_meta_value+0 '.$args['order'];
            }
        }
        //where固定字段参数
        $defaults['where'] = DcWhereFilter($args, ['id','title','name','slug','excerpt','password','parent','user_id','create_time','update_time','type','mime_type','views','hits','status','module','controll','action','meta_key','meta_value'], 'eq', 'info_');
        //where搜索参数
        if($args['search']){
            $defaults['where'] = DcArrayArgs(['info_title|info_name|info_slug|info_excerpt'=>['like','%'.DcHtml($args['search']).'%']],$defaults['where']);
            unset($args['search']);
        }
        //where分类法参数(视图查询)
        if( $args['term_id'] ){
            $defaults['where']['term_map.term_id'] = DcWhereValue($args['term_id'], 'in');
            unset($args['term_id']);
        }
        //where自定义参数合并
        if($args['where']){
            $defaults['where'] = DcArrayArgs($args['where'], $defaults['where']);
            unset($args['where']);
        }
        //返回结果
        return \daicuo\Info::result(\daicuo\Info::all(DcArrayArgs($args, $defaults)), DcEmpty($args['result'], 'array'));
    }
    
    /**
     * 按ID删除多个内容数据
     * @version 1.8.10 首次引入
     * @param array $post 必需;数组格式;默认:空
     * @param string $parentName 可选;父级名称;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function deleteIds($ids=[])
    {
        return \daicuo\Info::delete_all(['info_id'=>['in',$ids]]);
    }
    
    /**
     * 快速修改内容表的数据状态
     * @version 1.8.10 首次引入
     * @param array $ids 必须;ID列表;默认:空
     * @return int 影响条数
     */
    public function status($ids=[],$value)
    {
        $data = [];
        $data['info_status'] = $value;
        return dbUpdate('common/Info',['info_id'=>['in',$ids]], $data);
    }
    
    /**
     * 按ID快速获取一个队例数据
     * @version 1.8.10 首次引入
     * @param int $id 必须;ID值;默认:空
     * @param bool $cache 可选;是否缓存;默认:false
     * @return array 数组形式
     */
    public function getId($id=[], $cache=false)
    {
        return \daicuo\Info::get_id($id, $cache);
    }
    
    /**
     * 按条件获取父级ID
     * @version 1.8.10 首次引入
     * @param int $id 必须;ID值;默认:空
     * @param bool $cache 可选;是否缓存;默认:false
     * @return array 数组形式
     */
    public function parentId($args=[])
    {
        $args = DcArrayArgs($where,[
            'cache' => false,
            'field' => 'info_id',
            'with'  => '',
        ]);
        $info = $this->get($args);
        return intval($info['info_id']);
    }
    
    /**
     * 只获取模块的所有动态扩展字段KEY
     * @version 1.8.10 首次引入
     * @param string $module 必需;应用名；默认:common
     * @param string $controll 可选;控制器；默认:category
     * @param string $action 可选;操作名；默认:system
     * @return array 二维数组
     */
    public function metaKeys($module='index', $controll='info', $action='index')
    {
        $args = [];
        $args['module']   = DcEmpty($module,'index');
        $args['controll'] = DcEmpty($controll,'info');
        $args['action']   = str_replace('index','',$action);
        $keys = model('common/Field','loglic')->forms(DcArrayEmpty($args),'keys');
        return array_unique($keys);
    }
    
    /**
     * 获取模块的所有动态扩展字段列表
     * @version 1.8.10 首次引入
     * @param string $module 必需;应用名；默认:common
     * @param string $controll 可选;控制器；默认:category
     * @param string $action 可选;操作名；默认:system
     * @return array 二维数组
     */
    public function metaList($module='index', $controll='info', $action='index')
    {
        $args = [];
        $args['module']   = DcEmpty($module,'index');
        $args['controll'] = DcEmpty($controll,'info');
        $args['action']   = str_replace('index','',$action);
        return model('common/Field','loglic')->forms( DcArrayEmpty($args) );
    }
    
    //卸载应用时按条件删除内容表的数据
    public function unInstall($module='index')
    {
        if(config('database.type') == 'sqlite'){
            for($i=0;$i<1000;$i++){
                \daicuo\Info::delete_all(['info_module'=>$module]);
            }
        }else{
            \daicuo\Info::delete_all(['info_module'=>$module]);
        }
        return true;
    }
}