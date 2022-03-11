<?php
namespace app\common\loglic;

class Term
{
    protected $error = '';
    
    //获取错误信息
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * 新增或修改一条队列数据（有term_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $posts 必需;参考默认字段;默认：空
     * @return mixed obj|null
     */
    public function write($post=[])
    {
        $post['term_module']   = DcEmpty($post['term_module'],'index');
        
        $post['term_controll'] = DcEmpty($post['term_controll'],'category');
        
        if(in_array($post['term_module'],['index','common','admin'])){
            $validateName = 'common/Term';
        }else{
            $validateName = $post['term_module'].'/'.ucfirst($post['term_controll']);
        }
        
        config('common.validate_name', $validateName);//需要应用定义验证规则
        
        config('common.validate_scene', 'save');//验证场景
        
        config('common.where_slug_unique', ['term_module'=>['eq',$post['term_module']],'term_controll'=>['eq',$post['term_controll']]]);
        
        config('custom_fields.term_meta',$this->metaKeys($post['term_module'],$post['term_controll']));
        
        //修改
        if($post['term_id']){
            config('common.validate_scene', 'update');
            return \daicuo\Term::update_id($post['term_id'], $post);
        }
        
        //新增
        return \daicuo\Term::save($post);
    }
    
    //按条件删除队列（fori++循环清空）
    public function delete($args=[])
    {
        $where = DcWhereFilter($args, ['id','name','slug','module','controll','action','status','type','info','parent','count'], 'eq', 'term_');
        if(!$where){
            return false;
        }
        return \daicuo\Term::delete_all($where);
    }
    
    /**
     * 按条件获取一个分类
     * @version 1.6.0 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $field 可选;查询字段;默认：*
     *     @type mixed $status 可选;显示状态（normal|hidden）;默认：空
     *     @type mixed $type 可选;队列类型(stirng|array),固定范围(category|tag);默认：category
     *     @type mixed $module 可选;队列类型(stirng|array),固定范围(category|tag);默认：category
     *     @type mixed $id 可选;类型ID(int|array);默认：空
     *     @type mixed $name 可选;分类名称(stirng|array);默认：空
     *     @type mixed $slug 可选;分类别名(stirng|array);默认：空
     *     @type mixed $info 可选;分类描述(stirng|array);默认：空
     *     @type mixed $parent 可选;父级ID(int|array);默认：空
     *     @type mixed $count 可选;数量统计(int|array);默认：空
     *     @type mixed $meta_key 可选;扩展字段限制条件(string|array);默认：空
     *     @type mixed $meta_value 可选;扩展字段值限制条件(string|array);默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed $mixed 查询结果（array|null）
     */
    public function get($args=[])
    {
        //where动态字段参数
        $where = DcWhereFilter($args, ['id','name','slug','module','controll','action','status','type','info','parent','count','meta_key','meta_value'], 'eq', 'term_');
        //where动态数组参数
        if($args['where']){
            $args['where'] = DcArrayArgs($args['where'], $where);
        }else{
            $args['where'] = $where;
        }
        //返回结果
        return \daicuo\Term::meta_attr(\daicuo\Term::get(DcArrayEmpty($args)));
    }
    
    /**
     * 获取多条分类数据
     * @version 1.6.0 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $field 可选;查询字段;默认：*
     *     @type string $result 可选;返回结果类型(array|tree|obj);默认：array
     *     @type int $limit 可选;分页大小;默认：0
     *     @type int $page 可选;当前分页;默认：0
     *     @type string $sort 可选;排序字段名(term_id|term_parent|term_order|trem_count|term_meta_key|term_meta_value|meta_value_num);默认：op_order
     *     @type string $order 可选;排序方式(asc|desc);默认：asc
     *     @type string $search 可选;搜索关键词（名称与别名）;默认：空
     *     @type mixed $status 可选;显示状态（normal|hidden）;默认：空
     *     @type mixed $type 可选;队列类型(stirng|array),固定范围(category|tag);默认：category
     *     @type mixed $module 可选;队列类型(stirng|array),固定范围(category|tag);默认：category
     *     @type mixed $id 可选;类型ID(int|array);默认：空
     *     @type mixed $name 可选;分类名称(stirng|array);默认：空
     *     @type mixed $slug 可选;分类别名(stirng|array);默认：空
     *     @type mixed $info 可选;分类描述(stirng|array);默认：空
     *     @type mixed $parent 可选;父级ID(int|array);默认：空
     *     @type mixed $count 可选;数量统计(int|array);默认：空
     *     @type mixed $meta_key 可选;扩展字段限制条件(string|array);默认：空
     *     @type mixed $meta_value 可选;扩展字段值限制条件(string|array);默认：空
     *     @type array $meta_query 可选;自定义筛选字段(二维数组[key,value,compare]);默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     *     @type array $paginate 可选;自定义高级分页参数;默认：空
     * }
     * @return mixed $mixed obj|array|null 查询结果
     */
    public function select($args=[])
    {
        //基础定义
        $defaults = array();
        $defaults['cache']    = true;
        $defaults['group']    = '';
        $defaults['join']     = [];
        $defaults['with']     = 'term_meta';
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
            $defaults['field'] = 'term.*';
            $defaults['alias'] = 'term';
            $defaults['group'] = 'term.term_id';
            //join固定参数
            //array_push($defaults['join'],['term_meta','term_meta.term_id=term.term_id']);
            //where固定参数
            //array_push($defaults['where'],['term.term_type'=>['eq',DcEmpty($args['type'],'category')]]);
            //where动态参数
            foreach(DcWhereFilter($args, ['id','name','slug','module','controll','action','status','type','info','parent','count'], 'eq', 'term.term_') as $key=>$where){
                array_push($defaults['where'],[$key=>$where]);
            }
            //where动态拼装自定义字段多条件与JOIN
            foreach($args['meta_query'] as $key=>$where){
                //join参数拼装
                array_push($defaults['join'],['term_meta t'.$key, 't'.$key.'.term_id = term.term_id']);
                //where参数拼装
                $whereSon = [];
                if( isset($where['key']) ){
                    $whereSon['t'.$key.'.term_meta_key']  = DcWhereValue($where['key'],'eq');
                }
                if( isset($where['value']) ){
                    $whereSon['t'.$key.'.term_meta_value'] = DcWhereValue($where['value'],'eq');
                }
                if($whereSon){
                    array_push($defaults['where'], $whereSon);
                }
            }
            //where搜索参数
            if($args['search']){
                array_push($defaults['where'],['term.term_name|term.term_slug'=>['like','%'.DcHtml($args['search']).'%']]);
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
                $args['group'] = 'term.term_id,t0.term_meta_value';
                $args['orderRaw'] = 't0.term_meta_value+0 '.$args['order'];
            }
            unset($args['meta_query']);
            //返回结果
            return \daicuo\Term::result(\daicuo\Term::all(DcArrayArgs($args, $defaults)), DcEmpty($args['result'],'array'));
        }
        //是否视图查询
        if($args['meta_key'] || $args['meta_value']){
            //重置默认条件
            //$defaults['field'] = 'term.*';
            $defaults['group'] = 'term.term_id';
            $defaults['field'] = '';//强制为空下面定义的字段才有效
            $defaults['view']  = [
                ['term', '*'],
                ['term_meta', NULL, 'term_meta.term_id=term.term_id']
            ];
            //自定义字段排序处理
            if($args['sort'] == 'meta_value_num'){
                $args['sort'] = '';
                $args['group'] = 'term.term_id,term_meta.term_meta_value';
                $args['orderRaw'] = 'term_meta_value+0 '.$args['order'];
            }
        }
        //where固定字段参数
        $defaults['where'] = DcWhereFilter($args, ['id','name','slug','module','controll','action','status','type','info','parent','count','meta_key','meta_value'], 'eq', 'term_');
        //where搜索参数
        if($args['search']){
            $defaults['where'] = DcArrayArgs(['term_name|term_slug'=>['like','%'.DcHtml($args['search']).'%']], $defaults['where']);
            unset($args['search']);
        }
        //where自定义参数合并
        if($args['where']){
            $defaults['where'] = DcArrayArgs($args['where'], $defaults['where']);
            unset($args['where']);
        }
        //返回结果
        return \daicuo\Term::result(\daicuo\Term::all(DcArrayArgs($args, $defaults)), DcEmpty($args['result'], 'array'));
    }
    
    /**
     * 按ID删除多个队例ID数据
     * @version 1.8.10 首次引入
     * @param array $post 必需;数组格式;默认:空
     * @param string $parentName 可选;父级名称;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function deleteIds($ids=[])
    {
        return \daicuo\Term::delete_ids($ids);
    }
    
    /**
     * 快速修改队列表的数据状态
     * @version 1.8.10 首次引入
     * @param array $ids 必须;ID列表;默认:空
     * @return int 影响条数
     */
    public function status($ids=[],$value)
    {
        $data = [];
        $data['term_status'] = $value;
        return dbUpdate('common/Term',['term_id'=>['in',$ids]], $data);
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
        return \daicuo\Term::get_id($id, $cache);
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
            'field' => 'term_id',
            'with'  => '',
            //'module'   => 'admin',
            //'controll' => 'menus',
            //'name'     => '菜单',
        ]);
        $term = $this->get($args);
        return intval($term['term_id']);
    }
    
    /**
     * 通过队例名获取队列ID，不存在时自动新增
     * @version 1.8.0 首次引入
     * @param mixed $tagName 必需;队列名，多个用逗号分隔;默认：空
     * @param string $module 可选;应用名;默认：空
     * @param string $controll 可选;模块名;默认：空
     * @param bool $autoSave可选;是否自动新增;默认：false
     * @return array 查询结果
     */
    public function nameToId($name='', $module='index', $controll='category', $autoSave=false){
        //需要转化的名称
        if(!$name){
            return 0;
        }
        //标签名称处理
        if( is_string($name) ){
            $name = explode(',',$name);
        }
        //初始值
        $module   = DcEmpty($module,'index');
        $controll = DcEmpty($controll,'category');
        //查询条件
        $where = [];
        $where['term_name']     = ['in',$name];
        $where['term_module']   = ['eq',$module];
        $where['term_controll'] = ['eq',$controll];
        //查询数据库
        $list = DcArrayResult( db('term')->field('term_id,term_name')->where($where)->order('term_id desc')->select() );
        //只返回查询到的结果（不自动新增）
        if($autoSave == false){
            return array_column($list, 'term_id');
        }
        //定义队列关系（键名=队例名，键值=队例ID）
        $result = [];
        foreach($list as $key=>$value){
            $result[$value['term_name']] = $value['term_id'];
        }
        //遍历检测是否都存在
        foreach($name as $key=>$value){
            //新增不存在的队列
            if( !array_key_exists($value, $result) ){
                //写入数据并返回自增ID
                $result[$value] = db('term')->insertGetId([
                    'term_name'     => $value,
                    'term_slug'     => uniqid(),
                    'term_module'   => $module,
                    'term_controll' => $controll,
                    'term_action'   => 'index',
                ]);
            }
        }
        //返回合并后的队列ID
        return array_values($result);
    }
    
    /**
     * 只获取模块的所有动态扩展字段KEY
     * @version 1.8.10 首次引入
     * @param string $module 必需;应用名；默认:common
     * @param string $controll 可选;控制器；默认:category
     * @param string $action 可选;操作名；默认:system
     * @return array 二维数组
     */
    public function metaKeys($module='index', $controll='category', $action='index')
    {
        $args = [];
        $args['module']   = DcEmpty($module,'index');
        $args['controll'] = DcEmpty($controll,'category');
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
    public function metaList($module='index', $controll='category', $action='index')
    {
        $args = [];
        $args['module']   = DcEmpty($module,'index');
        $args['controll'] = DcEmpty($controll,'category');
        $args['action']   = str_replace('index','',$action);
        return model('common/Field','loglic')->forms( DcArrayEmpty($args) );
    }
    
    /**
     * 获取队例表站内链接
     * @version 1.8.10 首次引入
     * @param array $term 必需;[id,name,slug]；默认:空
     * @param mixed $pageNumber 可选;int|[PAGE];默认:空
     * @return string 内部网址链接
     */
    public function url($term=[], $pageNumber='')
    {
        $module   = DcEmpty($term['term_module'],'index');
        $controll = DcEmpty($term['term_controll'],'category');//category|tag|navs|menus
        $action   = DcEmpty($term['term_action'],'index');
        //伪静态规则
        $route = config($module.'.rewrite_'.$controll);
        //URL参数
        $args = [];
        if( preg_match('/:slug|<slug/i',$route) ){
            $args['slug'] = $term['term_slug'];
        }elseif( preg_match('/:name|<name/i',$route) ){
            $args['name'] = $term['term_name'];
        }else{
            $args['id'] = $term['term_id'];
        }
        if($pageNumber){
            $args['pageNumber'] = $pageNumber;
        }
        return DcUrl($module.'/'.$controll.'/'.$action, $args);
    }
    
    /**
     * 添加一条队列表的数据（基础数据与扩展数据）
     * @version 1.8.10 首次引入
     * @param array $post 必需;数组格式;默认:空
     * @param string $parent 可选;父级名称;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function install($post=[], $parent='')
    {
        //初始值
        $post = DcArrayArgs($post,[
            'term_name'        => 'termName',
            'term_slug'        => 'termSlug',
            'term_info'        => 'termInfo',
            'term_module'      => 'index',//应用名
            'term_controll'    => 'category',//模块名
            'term_action'      => 'index',//操作名
            'term_status'      => 'normal',//状态
            'term_type'        => '',
            'term_order'       => 1,
            'term_parent'      => 0,
            'term_count'       => 0,
            'term_title'       => '',
            'term_kewords'     => '',
            'term_description' => '',
        ]);
        
        config('common.validate_name', false);

        config('common.validate_scene', false);

        config('common.where_slug_unique', ['term_module'=>['eq',$post['term_module']],'term_controll'=>['eq',$post['term_controll']]]);
        
        config('custom_fields.term_meta',$this->metaKeys($post['term_module'],$post['term_controll']));
    
        //父级条件
        if($parent){
            $where = [];
            $where['term_module']   = ['eq',$post['term_module']];
            $where['term_controll'] = ['eq',$post['term_controll']];
            $where['term_name']     = ['eq',$parent];
            //查询父级ID
            $post['term_parent']    = db('term')->where($where)->value('term_id');
            //没找到父级不添加
            if(!$post['term_parent']){
                return false;
            }
        }
        
        //添加数据
        $termId = db('term')->where([
            'term_name'     => $post['term_name'],
            'term_controll' => $post['term_controll'],
            'term_module'   => $post['term_module'],
        ])->value('term_id');
        if(!$termId){
           return \daicuo\Term::save($post);
        }
        //默认返回
        return false;
    }
    
    //卸载应用时按条件删除队列表的数据
    public function unInstall($module='index')
    {
        if(config('database.type') == 'sqlite'){
            for($i=0;$i<1000;$i++){
                \daicuo\Term::delete_all(['term_module'=>$module]);
            }
        }else{
            \daicuo\Term::delete_all(['term_module'=>$module]);
        }
        return true;
    }
}