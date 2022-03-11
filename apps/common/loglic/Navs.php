<?php
namespace app\common\loglic;

class Navs
{
    /**
     * 新增或修改一个前台导航（有term_id时为修改）
     * @version 1.8.10 首次引入
     * @param array $posts 必需;参考默认字段;默认：空
     * @return mixed 查询结果obj|null
     */
    public function write($post=[])
    {
        $post['term_controll'] = 'navs';
        
        config('common.validate_name', 'common/Term');
        
        config('common.validate_scene', 'save');
        
        config('common.where_slug_unique', false);//标题唯一，禁用别名唯一
        
        config('custom_fields.term_meta',model('common/Term','loglic')->metaKeys($post['term_module'], $post['term_controll']));//动态扩展字段列表
        
        //修改
        if($post['term_id']){
            config('common.validate_scene', 'update');
            return \daicuo\Term::update_id($post['term_id'], $post);
        }
        
        //新增
        return \daicuo\Term::save($post);
    }
    
    /**
     * 按条件删除多个后台菜单
     * @version 1.8.10 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type string $module 可选;应用名称;默认：空
     * }
     * @return mixed 查询结果obj|null
     */
    public function delete($args=[])
    {
        $args = DcArrayArgs($args,[
            'module'     => 'index',
        ]);
        $args['controll'] = 'navs';
        return model('common/Term','loglic')->delete($args);
    }
    
    /**
     * 按条件查询一条前台导航
     * @version 1.8.0 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type mixed $id 可选;内容ID(stirng|array);默认：空
     *     @type mixed $name 可选;内容名称(stirng|array);默认：空
     *     @type mixed $slug 可选;内容别名(stirng|array);默认：空
     *     @type mixed $title 可选;内容别名(stirng|array);默认：空
     *     @type array $with 可选;自定义关联查询条件;默认：空
     *     @type array $view 可选;自定义视图查询条件;默认：空
     *     @type array $where 可选;自定义高级查询条件;默认：空
     * }
     * @return array 查询结果
     */
    function get($args=[])
    {
        $args = DcArrayArgs($args,[
            'cache' => true,
            'with'  => 'term_meta',
        ]);
        $args['controll'] = 'navs';
        return model('common/Term','loglic')->get($args);
    }

    /**
     * 按条件获取导航菜单列表
     * @version 1.0.0 首次引入
     * @param array $args 必需;查询条件数组格式 {
     *     @type bool $cache 可选;是否缓存;默认：true
     *     @type string $limit 可选;数据限制(分页时为每一页);默认：空
     *     @type string $page 可选;分页页码(不填则不分页);默认：空
     *     @type string $result 可选;返回状态(array|tree|level);默认：tree
     *     @type string $sort 可选;排序字段名(term_id|term_order|term_count);默认：term_order
     *     @type string $order 可选;排序方式(asc|desc);默认：asc
     *     @type string $status 可选;显示状态（normal|hidden）;默认：空
     *     @type string $module 可选;模型名称;默认：空
     *     @type string $controll 可选;控制器名称;默认：空
     *     @type string $type 可选;操作名称(navbar|sitebar|navs|links|ico|image|other);默认：空
     *     @type array  $where 可选;自定义高级查询条件;默认：空
     * }
     * @return mixed 查询结果obj|null
     */
    function select($args=[])
    {
        //手动处理返回结果
        $result = DcEmpty($args['result'], 'tree');
        //默认参数(固定返回Array)
        $default = [
            'cache'    => true,
            'result'   => 'array',
            'controll' => 'navs',
            'result'   => 'array',
            'sort'     => 'term_parent asc,term_order',
            'order'    => 'desc',
        ];
        //查询导航栏标识时不同的查询方法（需合并分类的导航栏属性）
        if($args['type'] == 'navbar'){
            $default['controll'] = ['in',['category','navs']];
            //$default['whereOr'][0] = 'term_controll="navs" and term_type = "navbar"';
        }
        //合初初始条件
        $args = DcArrayArgs($args,$default);
        //字段映射
        $navs = [];
        //调用查询接口后进行字段映射转换器
        foreach(model('common/Term','loglic')->select($args) as $key=>$value){
            $navs[$key] = $this->dataGet($value);
        }
        //树形结构
        if($result == 'tree' && $navs){
            return list_to_tree($navs, 'navs_id', 'navs_parent');
        }
        //还原层级
        if($result == 'level' && $items){
            return tree_to_level(list_to_tree($items, 'navs_id', 'navs_parent'), 'navs_name');
        }
        //普通数组
        return $navs;
    }
    
    /**
     * 按ID快速获取一个前台导航
     * @version 1.8.10 首次引入
     * @param int $id 必须;ID值;默认:空
     * @param bool $cache 可选;是否缓存;默认:false
     * @return array 数组形式
     */
    public function getId($id=[], $cache=false)
    {
        return $this->dataGet( \daicuo\Term::get_id($id, $cache) );
    }
    
    /**
     * 快速获取菜单父级ID
     * @version 1.8.10 首次引入
     * @param string $termName 必须;应用名;默认:空
     * @param string $termModule 可选;应用名;默认:空
     * @param string $termType 可选;应用名;默认:空
     * @return int ID值
     */
    public function parentId($termName='',$termModule='',$termType='')
    {
        $args = [
            'term_name'     => $termName,
            'term_module'   => $termModule,
            'term_type'     => $termType,
            'term_controll' => 'navs',
        ];
        return intval(db('term')->where(DcArrayEmpty($args))->value('term_id'));
    }

    //修改器（需返回动态扩展的字段）
    public function dataSet($post=[])
    {
        $fields = $this->fieldsFlip();
        $result = [];
        foreach($fields as $key=>$value){
            $result[$value] = $post[$key];
            unset($post[$key]);
        }
        return DcArrayArgs($result,$post);
    }
    
    //获取器（需返回动态扩展的字段）
    public function dataGet($data=[])
    {
        //链接处理
        if($data['term_controll'] == 'category'){
            $data['navs_link']   = model('common/Term','loglic')->url($data);
            $data['term_action'] = '_self';
        }else{
            $data['navs_link'] = $this->urlParse($data['term_slug']);
        }
        //字段映射
        $fields = $this->fieldsFlip();
        $result = [];
        foreach($fields as $key=>$value){
            $result[$key] = $data[$value];
            unset($data[$value]);
        }
        return DcArrayArgs($result,$data);
    }
    
    //字段映射规则
    public function fieldsFlip()
    {
        return [
            'navs_id'      => 'term_id',
            'navs_parent'  => 'term_parent',
            'navs_name'    => 'term_name',
            'navs_url'     => 'term_slug',
            'navs_type'    => 'term_type',
            'navs_info'    => 'term_info',
            'navs_active'  => 'term_title',
            'navs_ico'     => 'term_keywords',
            'navs_image'   => 'term_description',
            'navs_status'  => 'term_status',
            'navs_order'   => 'term_order',
            'navs_target'  => 'term_action',
            'navs_controll'=> 'term_controll',
            'navs_module'  => 'term_module',
        ];
    }
    
    /**
     * 内部模块链接与外部网址链接格式化
     * @version 1.8.0 首次引入
     * @param string $url 必需;待验证的网址;默认：空
     * @return string 转换后的链接
     */
    public function urlParse($url='')
    {
        if(!$url){
            return 'javascript:;';
        }
        //分解地址栏参数
        $array = parse_url($url);
        if($array['scheme']){
            return $url;
        }
        //内部链接
        return DcUrl($array['path'], $array['query']);
    }
    
    /**
     * 插件安装时批量添加导航菜单
     * @version 1.8.10 首次引入
     * @param array $posts 必需;二维数组格式;默认:空
     * @param string $parentName 可选;父级名称;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function install($posts=[], $parentName='')
    {
        foreach($posts as $key=>$post){
            //字段映射
            $posts[$key] = $this->dataSet($post);
            //固定值
            $posts[$key]['term_controll'] = 'navs';
            //父级ID
            if($parentName){
                $posts[$key]['term_parent'] = $this->parentId($parentName, $post['term_module'], $post['term_type']);
            }
        }
        //写入条件
        config('common.validate_name', false);
        config('common.validate_scene', false);
        config('common.where_slug_unique', false);
        config('custom_fields.term_meta', '');
        //批量添加
        return \daicuo\Term::save_all($posts);
    }
    
    /**
     * 插件卸载时批量删除导航菜单
     * @version 1.8.10 首次引入
     * @param string $module 必须;应用名;默认:空
     * @return mixed 成功时返回obj,失败时null
     */
    public function unInstall($module='index')
    {
        return \daicuo\Term::delete_all([
            'term_controll' => 'navs',
            'term_module'   => $module,
        ]);
    }
}