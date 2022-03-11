<?php
error_reporting(E_ERROR);
/**************************************************ThinkPHP数据库***************************************************/
/**
 * 普通数据转多对多关系,通常为post提交的一维数组数据(同一字段以数组形式多个值)
 * @version 1.2.0 首次引入
 * @param array $data 必需;待转化的数据;['trem_much_id'=>[1,2,3,4]]
 * @param string $fields 必需;需要转化的字段,多个用逗号分隔或传入数组;默认：空
 * @param string $prefix 必需;关联表名;默认：term_map
 * @return array $array 转化成TP入库的数据格式
 */
function DcDataToMuch($data, $fields='', $prefix='term_map'){
    if(empty($data) || empty($fields)){
        return $data;
    }
    if( is_string($fields) ){
        $fields = explode(',', $fields);
    }
    foreach($fields as $key=>$field){
        if( isset($data[$field]) ){
            foreach($data[$field] as $key2=>$value2){
                $data[$prefix][$key2][$field] = $value2;
            }
        }
        unset($data[$field]);
    }
    return $data;
}
/**
 * 普通数据转一对多关系,通常为post提交的一维数组数据
 * @version 1.2.0 首次引入
 * @param array $data 必需;待转化的数据
 * @param string $fields 必需;需要转化的字段,多个用逗号分隔或传入数组或二维字段格式数组;默认：空
 * @param string $prefix 必需;关联表名;默认：user_meta
 * @return array $array 转化成TP入库的数据格式
 */
function DcDataToMany($data, $fields='', $prefix='user_meta'){
    if(empty($data) || empty($fields)){
        return $data;
    }
    if( is_string($fields) ){
        $fields = explode(',', $fields);
    }
    //组装成TP关联写入格式
    $data[$prefix] = [];
    foreach($fields as $key=>$field){
        if(is_array($field)){
            if( isset($data[$key]) ){
                array_push($data[$prefix],[
                    $prefix.'_key'   => $key,
                    $prefix.'_value' => $data[$key],
                ]);
            }
            unset($data[$key]);
        }else{
            if( isset($data[$field]) ){
                array_push($data[$prefix],[
                    $prefix.'_key'   => $field,
                    $prefix.'_value' => $data[$field],
                ]);
            }
            unset($data[$field]);
        }
    }
    return $data;
}
/**
 * 一对多关系转普通数据,通常为数据库查询后的数据
 * @version 1.2.0 首次引入
 * @param array $data 必需;待转化的数据
 * @param string $prefix 必需;待修改的关联表名(可以理解为修改器);默认：user_meta
 * @return array $array 转化后的数据/一维数组
 */
function DcManyToData($data, $prefix='user_meta'){
    if(empty($data)){
        return $data;
    }
    $data_meta = array();
    foreach($data[$prefix] as $value){
        $data_meta[$value[$prefix.'_key']] = $value[$prefix.'_value'];
    }
    unset($data[$prefix]);
    return array_merge($data, $data_meta);
}
/**
 * 模型别名唯一值处理
 * @version 1.2.0 首次引入
 * @param string $table 必需;待验证的表名
 * @param string $value 必需;待验证字段值
 * @param string $id 可选;主键ID;默认：0
 * @return string $string 不是唯一值时自动添加-**
 */
function DcSlugUnique($table, $value, $id=0){
    //空值是唯一
    if( empty($value) ){
        return uniqid();
    }
    //原值返回
    if( config('common.where_slug_unique') === false ){
        return $value;
    }
    //查询是否存在
    $count = db($table)->where(config('common.where_slug_unique'))->where([$table.'_id'=>['neq',$id]])->where($table.'_slug',[['eq',$value],['like',$value.'-%'],'or'])->fetchSql(false)->count();
    //还原为空
    config('common.where_slug_unique',[]);
    //已存在
    if($count){
        $value = $value.'-'.($count+1);
    }
    return $value;
}
/**
 * 根据query参数生成查询条件
 * @version 1.2.0 首次引入
 * @param array $fields 必需;白名单字段
 * @param string $condition 可选;关系,eq|neq|gt|lt|in;默认：eq
 * @param array $requestParams 可选;数组格式的地址栏参数;默认：空
 * @return array $array 用于TP查询的Where条件
 */
function DcWhereQuery($fields=[], $condition='eq', $requestParams=[]){
    //是否自动获取query参数
    if(!$requestParams){
        $requestParams = request()->param();
    }
    //空值过滤
    $query = array_filter($requestParams, function($value){
        if($value || $value=='0'){
            return true;
        }
        return false;
    });
    //字段过滤
    return DcWhereFilter($query, $fields, $condition);
}
/**
 * 根据字段过滤查询参数
 * @version 1.6.0 首次引入
 * @param array $args 必需;参数列表
 * @param array $fields 必需;白名单字段
 * @param string $condition 可选;关系(eq|neq|gt|lt|in|like);默认：eq
 * @param string $prefix 可选;KEY前缀;默认：空
 * @param string $suffix 可选;KEY后缀;默认：空
 * @return array $array 只返回字段中的条件语句
 */
function DcWhereFilter($args=[], $fields=[], $condition='eq', $prefix='', $suffix=''){
    $where = array();
    foreach($args as $key=>$value){
        if( in_array($key, $fields) ){
            $where[$prefix.$key.$suffix] = DcWhereValue($value, $condition);
        }
    }
    return $where;
}
/**
 * 格式化Where查询参数为数组
 * @version 1.6.0 首次引入
 * @param mixed $value 必需;条件值(string|array);默认：空
 * @return array $array 返回TP的数组WHERE条件
 */
function DcWhereValue($value='', $compare='eq'){
    if(is_numeric($value)){
        return [$compare, DcHtml($value)];
    }
    if(is_string($value)){
        return [$compare, DcHtml($value)];
    }
    if( DcIsArray($value, true) ){
        return $value;//值为二维数组时，['a'=>1,'b'=>2]
    }else{
        return [$value[0], $value[1]];//值为普通数组时，['like','%keyword%']
    }
}
/**
 * 根据地址栏参数按数据表的扩展字段生成多条件查询参数
 * @version 1.7.11 首次引入
 * @param string $customs 必需;DcFields函数格式化后的扩展字段列表；默认：空
 * @param array $query 必需;地址栏请求参数;默认：空
 * @return array 适用于模型查询函数的meta_query选项
 */
function DcMetaQuery($customs='', $query=[]){
    if(!$customs || !$query){
        return [];
    }
    $result = [];
    //过滤非扩展字段
    $fields = DcArrayFilter($query, array_keys($customs));
    //循环处理扩展字段
    foreach($fields as $field=>$value){
        if($value || $value==0){
            //扩展字段查询关系(手动定义)
            $relation = $customs[$field]['relation'];
            /*扩展字段地址栏参数后加下划线定义
            if(!$relation){
                $relation = trim($query[$field.'_']);
            }*/
            //扩展字段URL参数值
            $value = trim($value);
            //查询关系合法验证
            if( !in_array($relation,['like','neq','gt','lt','egt','elt']) ){
                $relation = 'eq';
            }
            //模糊查询关系
            if($relation == 'like'){
                $value = '%'.$value.'%';
            }
            //追加查询参数
            array_push($result, [
                'key'   => ['eq', $field],
                'value' => [$relation, $value],
            ]);
        }
    }
    //返回查询参数
    return $result;
}
/*---------------------------------------------数据库模型操作------------------------------------------------------------*/
/**
 * 将数据添加至数据库
 * @version 1.1.0 首次引入
 * @param string $name 资源地址(common/Nav)
 * @param array $data 待写入数据(关联写入则包含二维数组)
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/user_meta
 * @return int $int 返回自增ID或0
 */
function DcDbSave($name, $data=[], $relationTables=''){
    //实例化模型
    $model = model($name);
    //获取主键
    $pk = $model->getPk();
    //是否需要关联新增
    if($relationTables){
        //基础数据
        $dataBase = [];
        $tableBase = $model->getTableFields();
        foreach($tableBase as $key=>$value){
            if( isset($data[$value]) ){
                $dataBase[$value] = $data[$value];
            }
        }
        //$model->allowField(true)->save($dataBase);
        $model->data($dataBase, true)->allowField(true)->isUpdate(false)->save();
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        foreach($relationTables as $key=>$tableName){
            if($relationData = $data[$tableName]){
                //驼峰转化
                $relationTable = camelize($tableName);
                //关联新增方式
                if( DcIsArray($relationData, true) ){
                    $model->$relationTable()->saveAll($relationData);
                }else{
                    $model->$relationTable()->save($relationData);
                }
            }
        }
    }else{
        $model->data($data, true)->allowField(true)->isUpdate(false)->save();
    }
    return $model->$pk;
}
/**
 * 删除一条数据
 * @version 1.1.0 首次引入
 * @param string $name 资源地址(common/Nav)
 * @param array $where 查询条件
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/如:user_meta
 * @return null|obj 不为空时返回修改后的obj
 */
function DcDbDelete($name, $where=[], $relationTables=''){
    $result = array();
    //模型实例化
    $model = model($name);
    //获取模型主键
    $modelPk = $model->getPk();
    //获取数据
    $data = $model->with($relationTables)->where($where)->find();
    if( is_null($data) ){
        return null;
    }
    //删除基础数据
    $result[0] = $data->delete();
    //是否需要关联删除
    if($relationTables){
        //$data->together($relationTables)->delete();//只能关联删除一对一
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        //删除关联数据
        foreach($relationTables as $key=>$tableName){
            $tableName = camelize($tableName);//驼峰转化
            array_push($result, $data->$tableName()->delete());//追加删除结果
        }
    }
    //缓存处理
    if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) ){
        if( !is_null($info) ){
            DcCacheTag($modelPk.'_'.$data->$modelPk, 'clear', 'clear');
            //DcCacheTag($name.'/Item', 'clear', 'clear');
        }
    }
    //定义删除结果
    $data->RESULT = $result;
    //返回数据
    return $data;
}
/**
 * 修改一条数据
 * @version 1.6.0 优化附加删除字段
 * @version 1.1.0 首次引入
 * @param string $name 资源地址(common/Nav)
 * @param array $where 更新条件
 * @param array $data 待写入数据(关联写入则包含二维数组)
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/user_meta
 * @param mixed $deleteFields 一对多关联更新时附加的删除条件(array|false),如：['term_meta_key']
 * @return obj|null 不为空时返回修改后的obj
 */
function DcDbUpdate($name, $where=[], $data=[], $relationTables='', $deleteFields='default'){
    //实例化模型
    $model = model($name);
    //获取模型主键
    $modelPk = $model->getPk();
    //数据主键过滤
    unset($data[$modelPk]);
    //数据查询
    $info = $model->get($where);
    if( is_null($info) ){
        return null;
    }
    //基础数据表强制更新（即使有主键ID）
    $info->allowField(true)->isUpdate()->save($data);
    //关联数据表更新
    if($relationTables){
        //关联数据表
        if(is_string($relationTables)){
            $relationTables = explode(',', $relationTables);
        }
        //关联操作
        foreach($relationTables as $key=>$tableName){
            //当前关联表数据
            $tableData    = $data[$tableName];
            //当前关联表名驼峰样式
            $tableNameCame = camelize($tableName);
            //关联数据验证
            if( empty($tableData) ){
                continue;
            }
            /**************************************************
            ** 采用哪种关联模式(由data关联表的数据决定,二维数组则是一对多)
            ** 多维数组采用一对多关联,先批量删除对应的值后再批量新增（model里定义hasMany）
            ** 普通数组采用一对一关联更新（model里定义hasOne）
            ********************************************************/
            if( DcIsArray($tableData, true) ){
                //默认附加删除条件xxx_key字段
                if($deleteFields == 'default'){
                    $deleteFields = [$tableName.'_key'];
                }
                
                //动态生成删除条件(按关联字段值+附加条件删除)
                $where = array();
                foreach($deleteFields as $deleteKey=>$deleteField){
                    foreach($tableData as $keyOne=>$valueOne){
                        if(isset($valueOne[$deleteField])){
                            $where[$deleteField][] = ['eq', $valueOne[$deleteField]];
                        }
                    }
                    if($where[$deleteField]){
                        array_push($where[$deleteField],'or');
                    }
                }
                //dump($info->$tableNameCame()->fetchSql(true)->where($where)->delete());
                $info->$tableNameCame()->where($where)->delete();
                
                //批量增加关联数据
                $info->$tableNameCame()->saveAll($tableData);
            }else{
                if( is_null($info->$tableNameCame) ){
                    $info->$tableNameCame()->save($tableData);//无关联数据时新增
                }else{
                    $info->$tableNameCame->isUpdate()->save($tableData);//已有关联数据时直接修改
                }
            }
            
        }
    }
    //缓存处理
    if( (config('cache.expire_detail') > 0) || (config('cache.expire_detail')===0) ){
        if( !is_null($info) ){
            DcCacheTag($modelPk.'_'.$info->$modelPk, NULL);
            DcCacheTag($name.'/Item', NULL);
        }
    }
    return $info;
}
/**
 * 查询单条数据
 * @version 1.1.0 首次引入
 * @param string $name 资源地址(common/Nav)
 * @param array $params 查询参数
 * @return obj|null 不为空时返回obj
 */
function DcDbFind($name, $params){
    $model = model($name);
    $modelPk = $model->getPk();
    $cacheExpire = config('cache.expire_detail');
    //
    $args = array();
    $args['field'] = '*';
    $args['alias'] = '';
    $args['where'] = [];
    $args['whereOr'] = [];
    $args['wheretime'] = '';
    //
    $args['join'] = [];
    $args['union'] = [];
    $args['view'] = [];
    //
    $args['relation'] = '';
    $args['with'] = [];
    $args['bind'] = '';
    //
    $args['sort'] = '';
    $args['order'] = '';
    //
    $args['fetchSql'] = false;
    $args['cache'] = true;
    $args['cacheKey'] = '';
    //合并参数
    if($params){
        $args = array_merge($args, $params);
    }
    //缓存管理
    if($args['cache'] && (false == $args['fetchSql']) ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            //缓存前缀
            $args['cacheKey'] = DcCacheKey($args);
            //无缓存的时候返回false
            if( $info = DcCache($args['cacheKey']) ){
                return $info;
            }
        }
    }
    //非关联条件
    if($args['fetchSql']){
        $model->fetchSql($args['fetchSql']);
    }
    if($args['field']){
        $model->field($args['field']);
    }
    //2021.06.12增加别名
    if($args['alias']){
        $model->alias($args['alias']);
    }
    if($args['where']){
        if( isset($args['where'][0]) ){
            foreach($args['where'] as $keyWhere=>$valueWhere){
                $model->where($valueWhere);
            }
        }else{
            $model->where($args['where']);
        }
    }
    if($args['whereOr']){
        if( isset($args['whereOr'][0]) ){
            foreach($args['whereOr'] as $keyWhereOr=>$valueWhereOr){
                $model->whereOr($valueWhereOr);
            }
        }else{
            $model->whereOr($args['whereOr']);
        }
    }
    if($args['wheretime']){
        $model->wheretime($args['wheretime']);
    }
    if($args['join']){
        $model->join($args['join']);
    }
    if($args['union']){
        $model->union($args['union']);
    }
    if($args['view']){
        $model->view($args['view']);
    }
    if($args['relation']){
        $model->relation($args['relation']);
    }
    if($args['with']){
        $model->with($args['with']);
    }
    if($args['bind']){
        $model->bind($args['bind']);
    }
    if($args['orderRaw']){
        $model->orderRaw($args['orderRaw']);
    }
    if( is_array($args['order']) ){
        $model->order($args['order']);
    }else{
        if($args['sort'] && $args['order'] ){
            $model->order($args['sort'].' '.$args['order']);
        }
    }
    //查询数据库
    $info = $model->find();
    //无结果
    if( is_null($info) ){
        return null;
    }
    //sql语句生成
    if( is_string($info) ){
        return $info;
    }
    //缓存写入
    if($args['cache'] && (false == $args['fetchSql']) && $args['cacheKey'] ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            DcCacheTag($modelPk.'_'.$info->$modelPk, $args['cacheKey'], $info, $cacheExpire);
        }
    }
    return $info;
}
/**
 * 查询多条数据
 * @version 1.1.0 首次引入
 * @param string $name 资源地址(common/Nav)
 * @param array $params 查询参数
 * @return obj|null|string 分页模询统一返回obj,sql语句为对象属性|为空时null不为空时obj,sql语句返回字符串
 */
function DcDbSelect($name, $params){
    $model = model($name);
    $cacheExpire = config('cache.expire_item');
    //
    $args = array();
    $args['field'] = '*';
    $args['alias'] = '';
    $args['where'] = [];
    $args['whereOr'] = [];
    $args['wheretime'] = '';
    //
    $args['group'] = '';
    $args['having'] = '';
    $args['join'] = [];
    $args['union'] = [];
    $args['view'] = [];
    //
    $args['hasWhere'] = [];
    $args['relation'] = '';
    $args['with'] = [];
    $args['bind'] = '';
    //
    $args['limit'] = 0;
    $args['page'] = 0;
    $args['sort'] = '';
    $args['order'] = '';
    $args['force'] = '';
    //
    $args['lock'] = false;
    $args['distinct'] = false;
    $args['paginate'] = [];
    //
    $args['cache'] = true;
    $args['fetchSql'] = false;
    //合并参数
    if($params){
        $args = array_merge($args, $params);
    }
    //缓存管理
    if($args['cache'] && (false == $args['fetchSql']) ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            $args['cacheKey'] = DcCacheKey($args);
            if( $list = DcCache($args['cacheKey']) ){
                return $list;
            }
        }
    }
    //取消hasWhere关联条件(因为只能一个表 有局恨性)
    if($args['field']){
        $model->field($args['field']);
    }
    //2021.06.12增加别名
    if($args['alias']){
        $model->alias($args['alias']);
    }
    //2021.06.12多维数组
    if($args['where']){
        if( isset($args['where'][0]) ){
            foreach($args['where'] as $keyWhere=>$valueWhere){
                $model->where($valueWhere);
            }
        }else{
            $model->where($args['where']);
        }
    }
    //2021.06.12多维数组
    if($args['whereOr']){
        if( isset($args['whereOr'][0]) ){
            foreach($args['whereOr'] as $keyWhereOr=>$valueWhereOr){
                $model->whereOr($valueWhereOr);
            }
        }else{
            $model->whereOr($args['whereOr']);
        }
    }
    if($args['wheretime']){
        $model->wheretime($args['wheretime']);
    }
    if($args['group']){
        $model->group($args['group']);
    }
    if($args['having']){
        $model->having($args['having']);
    }
    if($args['join']){
        $model->join($args['join']);
    }
    if($args['union']){
        $model->union($args['union']);
    }
    if($args['view']){
        $model->view($args['view']);
    }
    if($args['relation']){
        $model->relation($args['relation']);
    }
    if($args['with']){
        $model->with($args['with']);
    }
    if($args['bind']){
        $model->bind($args['bind']);
    }
    if($args['limit']){
        $model->limit($args['limit']);
    }
    if($args['page']){
        $model->page($args['page']);
    }
    if($args['orderRaw']){
        $model->orderRaw($args['orderRaw']);
    }
    if( is_array($args['order']) ){
        $model->order($args['order']);
    }else{
        if($args['sort'] && $args['order'] ){
            $model->order($args['sort'].' '.$args['order']);
        }
    }
    if($args['force']){
        $model->force($args['force']);
    }
    if($args['lock']){
        $model->lock($args['lock']);
    }
    if($args['distinct']){
        $model->distinct($args['distinct']);
    }
    if($args['fetchSql']){
        $model->fetchSql($args['fetchSql']);
    }
    if($args['paginate']){
        if($args['paginate'][1] === true){
            $list = $model->paginate($args['paginate'][0], true);//简洁分页模式
        }else{
           $list = $model->paginate($args['paginate']); 
        }
    }else{
        $list = $model->select();
        if( is_string($list) ){
            return $list;
        }
    }
    if($list->isEmpty()){
        return null;
    }
    //缓存写入
    if($args['cache'] && (false == $args['fetchSql']) && $args['cacheKey'] ){
        if( ($cacheExpire > 0) || ($cacheExpire===0) ){
            DcCacheTag($name.'/Item', $args['cacheKey'], $list, $cacheExpire);
        }
    }
    return $list;
}
/**
 * 模型的get方法查询单条数据
 * @version 1.1.0 首次引入
 * @param string $name 资源地址(common/Nav)
 * @param array $where 查询条件
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/如:user_meta
 * @return obj|null 不为空时返回obj
 */
function DcDbGet($name, $where=[], $relationTables=''){
    $model = model($name);
    //是否需要关联预载入查询
    if($relationTables){
        return $model->get($where, $relationTables);
    }else{
        return $model->get($where);
    }
}
/**
 * 模型的all方法查询多条数据
 * @version 1.1.0 首次引入
 * @param string $name 资源地址(common/Nav)
 * @param array $where 查询条件
 * @param array $relationTables 关联表/多个用,分隔/不需要表前缀/如:user_meta
 * @return obj|null 不为空时返回obj
 */
function DcDbAll($name, $where=[], $relationTables=''){
    $model = model($name);
    //是否需要关联预载入查询
    if($relationTables){
        //查询数据
        return $model->all($where, $relationTables);
    }else{
        return $model->all($where);
    }
}
/*---------------------------------------------数据库基础操作------------------------------------------------------------*/
/**
 * 添加一条数据
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $data 数据
 * @return int $int 返回记录数
 */
function dbInsert($name='', $data){
    return model($name)->allowField(true)->data($data)->save();//返回的是影响的记录数
    //return db($name)->insertGetId($data);//返回添加数据的自增主键
    //return db($name)->insert($data);返回添加成功的条数
}
/**
 * 添加多条数据
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $data 数据、二维数组
 * @return mixed $mixed 返回数据集(obj|null)
 */
function dbInsertAll($name='', $list){
    $status = model($name)->allowField(true)->saveAll($list, false);
    if($status->isEmpty()){
        return null;
    }
    return $status;
    //返回的是包含新增模型（带自增ID）的数据集（数组） 当数据中存在主键的时候会认为是更新操作 加上false强制新增
    //return db($name)->insertAll($data);
}
/**
 * 删除数据
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 条件
 * @return int $int 返回影响数据的条数，没有删除返回0
 */
function dbDelete($name='',$where){
    return model($name)->where($where)->delete();
}
/**
 * 更新数据
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 条件
 * @param array $data 数据
 * @return int $int 影响条数
 */
function dbUpdate($name='', $where=[], $data=[]){
    return model($name)->allowField(true)->save($data, $where);
    //return db($table)->where($where)->update($data);
}
/**
 * 批量更新数据(批量更新仅能根据主键值进行更新，其它情况请使用foreach遍历更新)
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $list 数据、二维数组(如果不包含主键则需要复合主键才可以成功)
 * @return int $int 影响条数
 */
function dbUpdateAll($name='', $list=[]){
    return model($name)->allowField(true)->isUpdate()->saveAll($list);//强制更新操作
}
/**
 * 更新某个字段的值
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 条件 
 * @param string field 字段名
 * @param string value 字段值
 * @return int $int 返回影响数据的条数，没修改任何数据字段返回 0
 */
function dbUpdateField($name='',$where=[], $field='', $value=''){
    return model($name)->where($where)->setField($field, $value);
}
/**
 * 自减某字段的值
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 条件
 * @param string field 字段名
 * @param string num 递增值
 * @param string time 延迟更新时长
 * @return int $int 影响数据的条数
 */
function dbUpdateDec($name='',$where=[], $field='', $num=1, $time=0){
    return model($name)->where($where)->setDec($field, $num, $time);
}
/**
 * 自增某字段的值
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 条件  
 * @param string field 字段名
 * @param string num 递增值
 * @param string time 延迟更新时长
 * @return int $int 影响数据的条数
 */
function dbUpdateInc($name='',$where=[], $field='', $num=1, $time=0){
    return model($name)->where($where)->setInc($field, $num, $time);
}
/**
 * 写入多条数据自动判断新增与修改,当数据中存在主键的时候会认为是更新操作,否则为新增
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $list 数据、二维数组
 * @return mixed $mixed 新增时为数据集,更新时为影响数据的条数（obj|int）
 */
function dbWriteAuto($name='', $list=[]){
    return model($name)->allowField(true)->saveAll($list);
    //返回的是包含新增模型（带自增ID）的数据集（数组） 当数据中存在主键的时候会认为是更新操作 否则为新增
}
/**
 * 数据查询单个格式：[模块/]控制器
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 查询条件
 * @param string $whereOr 查询条件
 * @param bool $fetchSql 显示查询语句
 * @return obj $obj 数据集|null
 */
function dbFind($name='', $where=[], $whereOr='', $cache=[], $fetchSql=false){
    if(!$name){
        return false;
    }
    //缓存参数初始化
    if(is_array($cache)){
        $cache = array_merge(['key'=>false,'time'=>false,'tag'=>false], $cache);
    }else{
        $cache = ['key'=>false,'time'=>false,'tag'=>false];
    }
    //缓存条件处理
    if($cache['key'] && $cache['time']){
        return model($name)->where($where)->whereOr($whereOr)->fetchSql($fetchSql)->cache($cache['key'],$cache['time'])->find();
    }else{
        return model($name)->where($where)->whereOr($whereOr)->fetchSql($fetchSql)->find();
    }
}
/**
 * 根据条件快捷查询某个字段的值
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 必需;查询条件;默认：空
 * @param string $field 必需;待查询的字段值;默认：空
 * @return mixed $mixed 获取的数据或null
 */
function dbFindValue($name='',$where=[], $field=''){
    if(!$name || !$where || !$field){
        return null;
    }
    return model($name)->where($where)->value($field);
}
/**
 * 数据查询多个
 * @version 1.0.0 首次引入
 * @param string $name Model名称
 * @param array $where 查询条件
 * @param array $params 查询参数
 * @return obj $obj 数据集|null
 */
function dbSelect($name='', $where=[], $params=[], $cache=[]){
    if(!$name){
        return false;
    }
    //缓存参数初始化
    if(is_array($cache)){
        $cache = array_merge(['key'=>false,'time'=>false,'tag'=>false], $cache);
    }else{
        $cache = ['key'=>false,'time'=>false,'tag'=>false];
    }
    //参数初始化
    $params = array_merge(['fetchSql'=>false,'field'=>'*'], $params);
    //分页
    if($params['page']){
        return model($name)->field($params['field'])->where($where)->order($params['sort'].' '.$params['order'])->fetchSql($params['fetchSql'])->cache($cache['key'],$cache['time'],$cache['tag'])->paginate($params['paginate']);
    }else{
        return model($name)->field($params['field'])->where($where)->limit($params['limit'])->order($params['sort'].' '.$params['order'])->fetchSql($params['fetchSql'])->cache($cache['key'],$cache['time'],$cache['tag'])->select();
    }
}

/**************************************************ThinkPhp语言***************************************************/
/**
 * 加载语言定义(不区分大小写)
 * @version 1.0.0 首次引入
 * @param string $file 必需;语言文件
 * @param string $range 可选;语言作用域;默认：空
 * @return mixed $mixed 语言配置信息
 */
function DcLoadLang($file, $range = ''){
    return \think\Lang::load($file, $range);
}

/**************************************************ThinkPhp验证器***************************************************/
/**
* 验证器独立验证
* @version 1.2.0 首次引入
* @param array $data 必需;待验证数据
* @param string $name 必需;验证器名称;默认：空
* @param string $scene 可选;验证场景,多个用逗号分隔;默认：空
* @param string $layer 可选;业务层名称;默认：validate
* @return bool $bool 验证失败时可使用cofig('daicuo.error')获取出错信息
*/
function DcCheck($data = [], $name, $scene='', $layer='validate'){
    $validate = validate($name, $layer);
    if(!$validate->scene($scene)->check($data)){
        config('daicuo.error', $validate->getError());
        return false;
    }
    return true;
}

/**************************************************ThinkPhp缓存***************************************************/
/**
 * 缓存标识处理函数、支持清空缓存(value=false)
 * @version 1.0.0 首次引入
 * @param string $key 必需;缓存KEY
 * @param string|array $value 必需;缓存数据（空|clear|false|data值）;默认：空
 * @param intval $time 可选;缓存时间;默认：0
 * @return mixed $mixed 缓存处理结果
 */
function DcCache($key, $value='', $time=0){
    if(!$key){
        return false;
    }
    if(is_null($value)){
        return \think\Cache::rm($key);//DcCache('key',null);
    }
    if($value === false || $value == 'clear'){
        return \think\Cache::clear();
    }
    if($value){
        return \think\Cache::set($key, $value, $time);
    }
    return \think\Cache::get($key);
}
/**
 * 缓存标签处理函数、支持清空缓存(value=false)
 * @version 1.0.0 首次引入
 * @param string $tag 必需;缓存标签名
 * @param string $key 必需;缓存标识KEY;默认：空
 * @param string|array $value 必需;缓存数据（空|clear|false|data值）;默认：空
 * @param intval $time 可选;缓存时间;默认：0
 * @return mixed $mixed 缓存结果
 */
function DcCacheTag($tag='', $key='', $value='', $time=0){
    if(!$tag){
        return false;
    }
    if(!$key || $key == 'clear' || $value === false || $value == 'clear'){
        return \think\Cache::clear($tag);//DcCacheTag('tag','clear','clear');
    }
    if($key && $value){
        return \think\Cache::tag($tag)->set($key, $value, $time);//DcCacheTag('tag','key','value',60);
    }
    return \think\Cache::get($key);
}
/**
 * 数据库结果处理后触发删除缓存
 * @version 1.0.0 首次引入
 * @param int|obj $result 必需;结果记录数或者数据集
 * @param string $cacheSign 可选;缓存标记（标识名或者标签名）;默认：空
 * @param string $delType 可选;缓存删除方式(key|tag);默认：key
 * @return mixed $mixed obj|array数据库查询对象
 */
function DcCacheResult($result, $cacheSign, $delType='key'){
    if($result && $cacheSign){
        if($delType == 'tag'){
            DcCacheTag($cacheSign, NULL, false);
        }else{
            DcCache($cacheSign, NULL);
        }
    }
    return $result;
}
/**
 * 生成缓存KEY名
 * @version 1.2.0 首次引入
 * @param string|array $value 必需;缓存名格式化
 * @return string $string 缓存KEY
 */
function DcCacheKey($value){
    if(is_array($value)){
        $value = DcArrayEmpty($value);
        return md5(serialize($value));
    }
    return md5($value);
}

/**************************************************ThinkPhp路径***************************************************/
/**
 * 获取系统根目录
 * @version 1.0.0 首次引入
 * @return string $string 框架根目录路径
 */
function DcRoot(){
    $base = \think\Request::instance()->root();
    return ltrim(dirname($base), DS).'/';
}
/**
 * 提取网址的域名
 * @version 1.0.0 首次引入
 * @param string $url 必需;调用地址
 * @return string $string 框架根目录路径
 */
function DcDomain($url=''){
    $url = parse_url($url);
    return $url['host'];
}
/**
 * 生成站内链接
 * @version 1.1.0 首次引入
 * @version 1.7.0 去掉后缀参数
 * @param string $url 必需;调用地址
 * @param string|array $vars 可选;调用参数，支持字符串和数组;默认：空
 * @return string $string 站内链接
 */
function DcUrl($url='', $vars=''){
    if(config('common.app_domain') == 'on'){
        return strip_tags(url($url, $vars, config('common.url_suffix'), true));
    }else{
        return strip_tags(url($url, $vars, config('common.url_suffix'), false));
    }
}
/**
 * 后台生成前台路径
 * @version 1.1.0 首次引入
 * @param string $url 必需;调用地址
 * @param string|array $vars 可选;调用参数 支持字符串和数组;默认：空
 * @return string $string 后台专用生成前台链接
 */
function DcUrlAdmin($url='', $vars=''){
	$baseFile = request()->baseFile();
	return str_replace($baseFile, '', DcUrl($url, $vars));
}
/**
 * 后台插件管理路径
 * @version 1.2.0 首次引入
 * @param array $vars 必需;地址栏参数
 * @param bool $suffix 可选;是否添加类名后缀;默认：true
 * @return string $string 后台插件访问地址
 */
function DcUrlAddon($vars=''){
    return DcUrl('addon/index', $vars);
    //return '../addon/index?'.http_build_query($vars);
}
/**
 * 附件读取路径
 * @version 1.3.0 首次引入
 * @param string $file 必需;文件保存的路径
 * @param int $key 可选;key值、第几张;默认：0
 * @return string $string 附件访问地址
 */
function DcUrlAttachment($file, $key=0){
    //必要参数
    if(!$file){
        return '';
    }
    //多图分割
    $file = explode(';',$file);
    //当前第几个
    $file = $file[$key];
    //判断本地附件还是远程附件
    $array = parse_url($file);
    //远程附件处理
	if(in_array($array['scheme'], array('http','https','ftp'))){
        //第三方防盗链附盗链开关
		if( config('common.upload_referer') ){
			return config('common.upload_referer').urlencode($file);
		}
        //直接返回绝对地址
		return $file;
	}
    //本地附件URL接口开关
    if(config('common.upload_host')){
        return config('common.upload_host').urlencode($file);
    }
    //本地附件CDN加速开关
    if(config('common.upload_cdn')){
        return trim(config('common.upload_cdn')).DcRoot().trim(config('common.upload_path')).'/'.$file;
    }
    //相对路径直接返回真实路径
    return DcRoot().trim(config('common.upload_path')).'/'.$file;
}
/**
 * 生成CSS引用链接
 * @version 1.6.0 首次引入
 * @param string $domain 必需;调用地址
 * @param string $path 可选;路径;默认：基础CSSe
 * @return string CSS链接
 */
function DcUrlCss($domain='', $path='/public/css/base.css'){
    return DcUrlJs($domain, $path);
}
/**
 * 生成Js引用链接
 * @version 1.6.0 首次引入
 * @param string $domain 必需;调用地址
 * @param string $path 可选;路径;默认：基础CSSe
 * @return string CSS链接
 */
function DcUrlJs($domain='', $path='/public/js/base.js'){
    if(config('common.app_domain') == 'on'){
        return $domain.$path.'?'.config('daicuo.version');
    }else{
        return $path.'?'.config('daicuo.version');
    }
}
/**
 * 解析网址并返回需要的字段
 * @version 1.7.0 首次引入
 * @param string $url 必需;待解析网址;默认:空
 * @param string $key 必需;scheme|host|query|path|fragment;默认：path
 * @return string 指定格式
 */
function DcParseUrl($url='',$key='path'){
    $url = parse_url($url);
    return $url[$key];
}

/****************************************************ThinkPhp配置***************************************************/
/**
* 获取系统配置.支持多级层次
* @version 1.2.0 首次引入
* @param string $name 必需;配置名称;默认：空
* @return mixed $mixed 获取到的配置值
*/
function DcConfig($name=''){
    if(!$name){
        return false;
    }
    $data = config();
    foreach (explode('.', $name) as $key => $val) {
        if (isset($data[$val])) {
            $data = $data[$val];
        } else {
            $data = null;
            break;
        }
    }
    return $data;
}
/**
 * 加载配置文件（PHP格式）
 * @version 1.0.0 首次引入
 * @param string $file 必需;配置文件名;默认：空
 * @param string $name 可选;配置名（如设置即表示二级配置）;默认：空
 * @param string $range 可选;作用域;默认：空
 * @return mixed $mixed 配置信息
 */
function DcConfigLoad($file, $name = '', $range = ''){
    return \think\Config::load($file, $name, $range);
}
/**
* 合并配置信息
* @version 1.4.0 首次引入
* @param string $config_name 必需;配置名称;默认空
* @param string $config_value 必需;新配置值;默认空
* @return mixed $mixed 合并后的配置值
*/
function DcConfigMerge($config_name='', $config_value=''){
    if( !is_array($config_value) ){
        return false;
    }
    //旧配置
    $config_name = trim($config_name);
    $config_value_old = config($config_name);
    if( is_string($config_value_old) ){
        $config_value_old = [$config_value_old];
    }elseif( is_null($config_value_old) ){
        $config_value_old = [];
    }
    if( !is_array($config_value_old) ){
        return false;
    }
    return config($config_name, array_merge($config_value_old, $config_value));
}

/**************************************************ThinkPhp模板***************************************************/
/**
 * 获取模板主题存放目录路径
 * @version 1.1.0 首次引入
 * @param string $module 必需;模块名称
 * @param bool $isMobile 必需;是否移动端
 * @return string $string 模板主题路径
 */
function DcViewPath($module, $isMobile){
    return 'apps/'.$module.'/theme/'.DcTheme($module, $isMobile).'/';
}
/**
 * 获取模板主题目录名称
 * @version 1.8.46 增加cookie设置
 * @version 1.1.0 首次引入
 * @param string $module 必需;模块名称;默认：index
 * @param bool $isMobile 必需;是否移动端;默认：false
 * @return string $string 模板主题名称
 */
function DcTheme($module='index', $isMobile=false){
    if( $theme = cookie('theme_'.$module )){
        return DcHtml($theme);
    }
    if($isMobile){
        return DcEmpty(config($module.'.theme_wap'),config('common.wap_theme'));
    }
    return DcEmpty(config($module.'.theme'),config('common.site_theme'));
}
/**
 * 获取插件应用的主题列表
 * @version 1.6.0 首次引入
 * @param string $module 必需;模块名称;默认：index
 * @return array $array 主题列表
 */
function DcThemeOption($module='index'){
    $themes = array();
    foreach( glob_basename('./apps/'.$module.'/theme/') as $value){
        $themes[$value] = $value;
    }
    return $themes;
}
/**
 * 生成模板调用配置标签
 * @version 1.4.0 首次引入
 * @param string $module 模块
 * @param string $field 字段
 * @return string $string 原生输出;
 */
function DcTplLabelOp($module='', $field=''){
    return DcHtml('{:config("'.$module.'.'.$field.'")}');
}

/**************************************************ThinkPhp钩子***************************************************/
/**
* 动态添加行为扩展到某个标签
* @version 1.2.0 首次引入
* @param string $tag 必需;标签名称
* @param mixed $behavior 必需;行为名称
* @param bool $first 可选;是否放到开头执行;默认：false
* @return void $void 不返回值
*/
function DcHookAdd($tag, $behavior, $first = false){
    \think\Hook::add($tag, $behavior, $first);
}
/**
 * 监听标签的行为
 * @version 1.2.0 首次引入
 * @param string $tag 必需;标签名称
 * @param mixed $params 可选;传入参数;默认：null
 * @param mixed $extra 可选;额外参数;默认：null
 * @param bool $once 可选;只获取一个有效返回值;默认：false
 * @return void $void 不返回值
 */
function DcHookListen($tag, &$params = null, $extra = null, $once = false){
    \think\Hook::listen($tag, $params, $extra, $once);
}
/**
 * 执行某个标签行为
 * @version 1.2.0 首次引入
 * @param mixed $class 必需;要执行的行为
 * @param string $tag 可选;方法名（标签名）;默认：空
 * @param mixed $params 可选;传入的参数;默认：null
 * @param mixed $extra 可选;额外参数;默认：null
 * @return void $void 不返回值
 */
function DcHookExec($class, $tag = '', &$params = null, $extra = null){
    \think\Hook::exec($class, $tag, $params, $extra);
}

/**************************************************字段，表单，表格***************************************************/
/**
 * 普通字段转框架表单字段格式（键名为字段名，键值为属性数组）
 * @version 1.7.12 attr属性控制筛选
 * @version 1.6.0 首次引入
 * @param mixed $fields 必需;表单字段数组列表或表名(array|string)；默认：空
 * @param array $datas 可选;默认数据,键名=>键值;默认：空
 * @return array $array 适用于表单字段与表格字段字义的格式
 */
function DcFields($fields=[], $data=[]){
    //无数据时
    if(!$fields){
        return null;
    }
    //字符串时查询根据表名获取字段
    if(is_string($fields)){
        $fields = db($fields)->getTableInfo('','fields');
    }
    //拼装成表单字段专用配置格式
    $result = [];
    foreach($fields as $key=>$field){
        if(is_array($field)){
            //二维数组，可自定义其它HTML元素属性（如type,value,title）（['term_tpl'=>['type'=>'textarea','value'=>123,'title'=>'test']]）
            $result[$key] = DcArrayArgs($field, [
                'order'        => intval($field['order']),
                'type'         => 'text',
            ]);
            //自定义函数名处理字段初始值
            if($field['value']){
                if(function_exists($field['value'])){
                    $result[$key]['value'] = call_user_func($field['value'], $data[$key]);
                }else{
                    $result[$key]['value'] = DcEmpty($data[$key],$field['value']);
                }
            }else{
                $result[$key]['value'] = $data[$key];
            }
            //自定义函数名处理字段选项值
            if($field['option']){
                if(function_exists($field['option'])){
                    $result[$key]['option'] = call_user_func($field['option'], $data[$key]);
                }
            }
        }else{
            //普通数组，只需要定义表单字段名(['term_tpl','term_hook'])
            $result[$field] = [
                'order'        => $key+1,
                'type'         => 'text',
                'value'        => $data[$field],
                'option'       => [],
                //'required'   => true,
                'data-filter'  => true,
                'data-visible' => true,
                'data-width'   => 120,
                //'data-class' =>' test',
            ];
        }
    }
    //返回结果
    return $result;
}
/**************************************************表单***************************************************/
/**
 * 快速生成表单元素
 * @version 1.0.0 首次引入
 * @param array $args 必需;表单元素属性列表 {
 *     @type string $name 必需;form标签的name属性，可通过此参数搭配钩子处理系统预设的表单;默认：text
 *     @type string $class 可选;form标签的class属性;默认：row form-group
 *     @type string $action 可选;form标签的action属性;默认：空
 *     @type string $method 可选;form标签的method属性;默认：post
 *     @type bool $disabled 可选;form标签的disabled属性;默认：false
 *     @type bool $ajax 可选;是否采用AJAX模式提交表单;默认：true
 *     @type string $callback 可选;AJAX模式时提交后回调函数;默认：空
 *     @type string $submit 可选;提交按钮文字,留空不显示;默认：提交
 *     @type string $reset 可选;重置按钮文字,留空不显示;默认：重置
 *     @type string $close 可选;关闭按文字,留空不显示,主要用于ajax浮动窗口;默认：字段标题
 *     @type string $items 可选;表单元素列表,参考DcFormItems参数;默认：空
 * }
 * @return array $array 框架表单元素属性专用格式
 */
function DcBuildForm($args=[]){
    return widget('common/Form/build', ['args'=>$args]);
}
/**
 * 快速生成适用于框架的表单元素格式列表
 * @version 1.6.0 首次引入
 * @param array $fields 必需;多维数组,键名为表单字段,键值为表单元素属性数组(参考DcFormItem参数);默认：空
 * @return array $array 框架表单元素属性专用格式
 */
function DcFormItems($fields=[]){
    foreach($fields as $field=>$attr){
        if($attr['type']){
            $fields[$field] = DcFormItem($field, $attr);
        }else{
            unset($fields[$field]);
        }
    }
    return $fields;
}
/**
 * 快速生成一条适用于框架的表单元素格式
 * @version 1.6.0 首次引入
 * @param string $field 必需;表单字段名；默认：空
 * @param array $attr 必需;表单元素属性列表 {
 *     @type string $type 必需;input类型(html|hidden|type|url|email|number|password|image|file|datetime|textarea|editor|json|custom|select|switch|radio|checkbox);默认：text
 *     @type string $html 可选;自定义html标签;默认：空
 *     @type string $hidden 可选;hidden属性;默认：空
 *     @type string $name 可选;name属性;默认：表单字段名
 *     @type string $id 可选;id属性;默认：表单字段名
 *     @type string $value 可选;value属性;默认：空
 *     @type string $title 可选;title属性;默认：字段标题
 *     @type string $placeholder 可选;placeholder属性;默认：字段描述
 *     @type string $tips 可选;表单元素提示;默认：空
 *     @type string $autofocus 可选;自动获取焦点属性;默认：空
 *     @type string $readonly 可选;readonly属性;默认：false
 *     @type string $disabled 可选;disabled属性;默认：false
 *     @type string $required 可选;required属性;默认：false
 *     @type string $option 可选;option属性，有效范围(select|suctom|switch|radio|checkbox);默认：空
 *     @type string $rows 可选;rows属性，有效范围(textarea|json|editor);默认：10
 *     @type string $class 可选;表单外层class属性;默认：row form-group
 *     @type string $class_left 可选;表单元素左侧class属性;默认：col-12 col-md-2
 *     @type string $class_right 可选;表单元素右侧class属性;默认：col-12 col-md-6
 *     @type string $class_right_controll 可选;表单元素右侧input标签class属性;默认：'form-control form-control-sm'
 *     @type string $class_right_label 可选;表单元素右侧label标签class属性，有效范围(switch|radio|checkbox);默认：'form-check-la'
 *     @type string $class_tips 可选;表单提示信息class属性;默认：'form-text text-muted small'
 * }
 * @return array $array 框架表单元素属性专用格式
 */
function DcFormItem($field='', $attr=[]){
    //默认属性
    $default = [
        'type'                => 'text',
        'name'                => $field,
        'id'                  => $field,
        'value'               => '',
        'title'               => lang(str_replace('[]','',$field)),
        //'placeholder'         => lang(str_replace('[]','',$field).'_placeholder'),
        'placeholder'         => '',
        'tips'                => '',
        'autofocus'           => '',
        'readonly'            => false,
        'disabled'            => false,
        'required'            => false,
        'class'               => 'row form-group',
        'class_left'          => 'col-12 col-md-2',
        'class_right'         => 'col-12 col-md-6',
        'class_right_control' => '',
        'class_tips'          => 'col-12 col-md-4 form-text text-muted small',
    ];
    //表单类型特有属性
    if( in_array($attr['type'],['text','url','email','number','password','image','file','datetime']) ){
        $default['autocomplete']      = 'off';
        $default['maxlength']         = '250';
    }elseif( in_array($attr['type'],['textarea','editor','json']) ){
        $default['rows']              = 10;
    }elseif( in_array($attr['type'],['custom','select']) ){
        $default['option'] = '';
        if($attr['multiple']){
            $default['name'] = $field.'[]';
            $default['id'] = $field.'[]';
        }
    }elseif( in_array($attr['type'],['switch','radio']) ){
        $default['class_right_label'] = '';
        $default['option']            = '';
    }elseif( in_array($attr['type'],['checkbox']) ){
        $default['name'] = $field.'[]';
        $default['id'] = $field.'[]';
        $default['class_right_label'] = '';
        $default['option'] = '';
    }elseif( in_array($attr['type'],['tags']) ){
        $default['class_tags']        = 'form-text pt-2';
        $default['class_tags_list']   = 'text-danger mr-2';
        $default['option']            = [];
    }
    //返回结果
    return DcArrayArgs($attr, $default);
}
/**
 * 通过框架表单字段生成表格筛选的固定样式（与表单生成函数类似，差别在于固定为text,select样式）
 * @version 1.8.1 增加data-type属性
 * @version 1.6.0 首次引入
 * @param array $items 必需;多维数组,键名为表单字段,键值参考DcFormItem参数列表;默认：空
 * @return array $array 框架表单元素属性专用格式
 */
function DcFormFilter($fields=[]){
    foreach($fields as $key=>$value){
        if( !$value['data-filter'] ){
            unset($fields[$key]);
            continue;
        }
        $fields[$key]['placeholder'] = '';
        $fields[$key]['class']       = 'form-group mt-2 mb-0 col-6 col-md-2';
        $fields[$key]['class_left']  = 'w-100';
        $fields[$key]['class_right'] = 'w-100';
        $fields[$key]['class_right_control'] = 'form-control form-control-sm dc-filter';
        //input类型处理
        if($fields[$key]['type'] == 'select'){
            $fields[$key]['multiple'] = false;
            $fields[$key]['size']     = 1;
            $fields[$key]['name']     = str_replace('[]','',$value['name']);
        }elseif( !in_array($fields[$key]['type'],['custom','switch','datetime']) ){//'radio','checkbox'
            $fields[$key]['type'] = 'text';
            $fields[$key]['name'] = str_replace('[]','',$value['name']);
        }
        //2022.02.23
        if(isset($value['data-type'])){
            $fields[$key]['type'] = $value['data-type'];
        }
        if(isset($value['data-value'])){
            $fields[$key]['value'] = $value['data-value'];
        }
        if(isset($value['data-option'])){
            $fields[$key]['option'] = $value['data-option'];
        }
    }
    return widget('common/Form/filter', ['args' => DcFormItems($fields)]);
}
/**************************************************表格***************************************************/
/**
 * 生成bootstrapTable表单HTML代码
 * @version 1.1.0 首次引入
 * @param array $config 表格参数列表
 * @return string $string 生成渲染后的表单HTML代码
 */
function DcBuildTable($args=[]){
    return widget('common/Table/build', ['args'=>$args]);
}
/**
 * 框架表单字段转bootstrapTable表单列属性格式
 * @version 1.7.12 转化格式前先进行字段排序
 * @version 1.6.0 首次引入
 * @param array $fields 必需;多维数组,键名为表单字段,键值参考DcFormItem参数列表;默认：空
 * @return array $array 框架表格元素属性专用格式
 */
function DcTableColumns($fields=[]){
    //表格列默认属性
    $column = [
        'data-escape'     => true,
        'data-visible'    => true,
        'data-align'      => 'center',
        'data-halign'     => 'center',
        /*
        'data-valign'     => 'middle',
        'data-falign'     => 'center',
        'data-width'      => '80',
        'data-width-unit' => 'px',
        'data-sortable'   => true,
        'data-sort-name'  => 'op_id',
        'data-order'      => 'asc',
        'data-class'      => '',
        'data-events'     => 'daicuo.admin.table.events',
        'data-formatter'  => 'daicuo.admin.table.formatter',
        */
    ];
    //表格字段排序
    $fields = list_sort_by($fields, 'order', 'asc', true);
    //字段过滤
    foreach($fields as $field=>$value){
        if( !$value['data-visible'] ){
            unset($fields[$field]);
            continue;
        }
        //过滤非data-属性
        foreach($value as $key=>$val){
            if(!stristr($key, 'data-')){
                unset($value[$key]);
            }
        }
        //定义必要参数
        $value['data-field'] = $field;
        $value['data-title'] = DcEmpty($value['data-title'],lang($field));
        //合并初始参数
        $fields[$field] = DcArrayArgs($value, $column);
    }
    //操作列（末列）
    $fields['operate']['data-escape']     = false;
    $fields['operate']['data-align']      = 'center';
    $fields['operate']['data-halign']     = 'center';
    $fields['operate']['data-field']      = 'operate';
    $fields['operate']['data-title']      = lang('operate');
    $fields['operate']['data-width']      = '100';
    $fields['operate']['data-width-unit'] = 'px';
    $fields['operate']['data-events']     = 'daicuo.admin.table.events';
    $fields['operate']['data-formatter']  = 'daicuo.admin.table.operate';
    //选择框列（首列）
    array_unshift($fields,['data-checkbox' => true]);
    //适用于tableColumn格式的数组
    return $fields;
}

/**************************************************采集内核***************************************************/
/**
 * 采集远程数据
 * @version 1.1.0 首次引入
 * @param string $useragent 必需;模拟用户HEAD头,可选值有（auto|windows|linux|ios|iphone）;默认：auto
 * @param int $timeout 必需;超时时间;默认：10
 * @param string $url 必需;待抓取的远程地址;默认：空
 * @param array $post_data 可选;post请求时发送的数据，留空则为get请求;默认：空
 * @param string $referer 可选;模拟来湃URL地址;默认：空
 * @param array $headers 可选;自定义请求头;默认：空
 * @param string $cookie 可选;模拟cookie信息;默认：空
 * @param string $proxy 可选;代理请求信息;默认：空
 * @return string $string 返回读取远程网页的内容
 */
function DcCurl($useragent='auto', $timeout=10, $url, $post_data='', $referer='', $headers=[], $cookie='', $proxy=''){
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_HEADER, 0);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);//301 302
    curl_setopt ($ch, CURLOPT_ENCODING, "");//乱码是因为返回的数据被压缩过了，在curl中加上一项参数即可
    //useragent
    if($useragent == 'windows'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows;U;WindowsNT6.1;en-us)AppleWebKit/534.50(KHTML,likeGecko)Version/5.1Safari/534.50');
    }elseif($useragent == 'linux'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:20.0) Gecko/20100101 Firefox/20.0');
    }elseif($useragent == 'ios'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh;U;IntelMacOSX10_6_8;en-us)AppleWebKit/534.50(KHTML,likeGecko)Version/5.1Safari/534.50');
    }elseif($useragent == 'iphone'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_2 like Mac OS X; zh-CN) AppleWebKit/537.51.1 (KHTML, like Gecko) Mobile/14F89 UCBrowser/10.9.17.807 Mobile');
    }elseif($useragent == 'android'){
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 7.1.1; zh-cn; OPPO R11st Build/NMF26X) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/53.0.2785.134 Mobile Safari/537.36 OppoBrowser/4.6.5.3');
    }    
    //是否post
    if(is_array($post_data)){
        curl_setopt($ch, CURLOPT_POST, 1);// post数据
        if($headers[0] == 'Content-Type: application/json'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));    // post的变量
        }else{
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);    // post的变量
        }
    }
    //是否伪造来路
    if($referer){
        curl_setopt ($ch, CURLOPT_REFERER, $referer);
    }
    //是否headers
    if(is_array($headers)){
        //$headers = array('X-FORWARDED-FOR:28.58.88.10','CLIENT-IP:225.28.58.32');//构造IP
        curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
    }    
    //是否cookie
    if($cookie){
        curl_setopt ($ch, CURLOPT_COOKIE, $cookie);
    }
    //IP代理
    if($proxy){
        curl_setopt ($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt ($ch, CURLOPT_PROXYPORT, "80");
        //curl_setopt ($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC); //代理认证模式
        //curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //使用http代理模式
        //curl_setopt ($ch, CURLOPT_PROXYUSERPWD,'testuser:pass');
    }    
    //https自动处理
    $http = parse_url($url);
    if($http['scheme'] == 'https'){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    $content = curl_exec($ch);
    curl_close($ch);
    //
    if($content){
        return $content;
    }
    return false;
}
/**
 * 根据正则规则快捷提取内容
 * @version 1.0.0 首次引入
 * @param string $rule 必需;正则规则
 * @param string $html 必需;待提取的字符串
 * @return string $string 匹配后的字符串
 */
function DcPregMatch($rule,$html){
    $arr = explode('$$$',$rule);
    if(count($arr) == 2){
      preg_match('/'.$arr[1].'/', $html, $data);
        return $data[$arr[0]].'';
    }else{
      preg_match('/'.$rule.'/', $html, $data);
        return $data[1].'';
    }
}

/**************************************************字符串、Array、Xml、Json、Serialize***************************************************/
/**
 * 字符串DES加密
 * @version 1.6.0 首次引入
 * @param string $str 必需;待加密的字符串
 * @param string $secert 必需;加密密钥
 * @return string $string 过滤后的字符串
*/
function DcDesEncode($str, $secert='daicuo'){
    return openssl_encrypt($str, 'DES-ECB', trim($secert), 0);
}
/**
 * 字符串DESDES解密
 * @version 1.6.0 首次引入
 * @param string $str 必需;待解密的字符串
 * @param string $secert 必需;加密密钥
 * @return string $string 解密后的字符串
 */
function DcDesDecode($str, $secert='daicuo'){
    return openssl_decrypt($str, 'DES-ECB', trim($secert), 0);
}
/**
 * 字符串转拼音
 * @version 1.6.0 首次引入
 * @param string $string 必需;待转化的字符;默认：空
 * @return string $string 转换后的拼音
 */
function DcPinYin($string){
    return \daicuo\Pinyin::get(trim($string));
}
/**
 * 字符串安全输出去除xss漏洞
 * @version 1.6.0 首次引入
 * @param string $string 必需;待过滤的字符串
 * @return string $string 过滤后的字符串
 */
function DcRemoveXss($string){
    return remove_xss($string);
}
/**
 * 字符串安全输出过滤目录名称不让跳转到上级目录
 * @version 1.6.0 优化过滤..与.
 * @version 1.5.0 首次引入
 * @param string $string 必需;待过滤的字符串
 * @return string $string 过滤后的字符串
 */
function DcDirPath($string){
    //if(!preg_match(“/^\w+$/”,$string)) exit(‘err!!!');//[A-Za-z0-9_]
    return str_replace(['..','.'], '', trim($string));
}
/**
 * 字符串安全输出去除Html标签
 * @version 1.5.0 首次引入
 * @param string $string 必需;待过滤的字符串
 * @param string $allow 可选;需保留的标签;默认：<p>
 * @return string $string 过滤后的字符串
 */
function DcStrip($string, $allow='<p>'){
    $string = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $string);
    $string = strip_tags(htmlspecialchars_decode(trim($string)), $allow);
    return stripslashes($string);
}
/**
 * 字符串安全输出转义HTML实体
 * @version 1.1.0 首次引入
 * @param string $string 必需;待过滤的字符串
 * @return string $string 过滤后的字符串
 */
function DcHtml($string){
    return htmlspecialchars(trim($string), ENT_QUOTES);
}
/**
 * %分隔的错误输出
 * @version 1.4.0 首次引入
 * @param string $value 必需;待分割的字符串
 * @return string $string 截取后的字符串
 */
function DcError($value){
    $value_array = explode('%', $value);
    if(count($value_array) > 1){
        return $value_array[1];
    }
    return $value;
}
/**
 * BOOL快捷输出
 * @version 1.3.0 首次引入
 * @param string $value 必需;待验证的字符串
 * @param bool $default 可选;验证规则;默认：true
 * @return string $string true|false
 */
function DcBool($value, $default=true){
    $array = ['1', 'true', 'on', 'yes'];
    if(in_array(strtolower($value), $array)){
        return $default;
    }
    return false;
}
/**
 * OnOff快捷输出
 * @version 1.3.0 首次引入
 * @param string $value 必需;待验证的字符串
 * @return string $string on|off
 */
function DcSwitch($value){
    if( DcBool($value) ){
        return 'on';
    }
    return 'off';
}
/**
 * 检测变量是否定义并默认输出
 * @version 1.6.0 首次引入
 * @param string $value 必需;待验证的字符串
 * @param mixed $default 必需;默认值;默认：空
 * @return string $string 验证后的字符串
 */
function DcIsset($value, $default=''){
    return isset($value) ? $value : $default;
}
/**
 * 字符串作比较是否相同后输出不同值
 * @version 1.1.0 首次引入
 * @param string $value 必需;待验证的字符串
 * @param string $default 必需;待比较的字符串;默认：空
 * @param string $stringTrue 必需;比较结果为真时输出的字符;默认：active
 * @param string $empty 必需;比较结果为假时输出的字符;默认：空
 * @return string $string 验证后的字符串
 */
function DcDefault($value, $default, $stringTrue='active', $stringFalse=''){
  if($value == $default){
    return $stringTrue;
  }
  return $stringFalse;
}
/**
 * 字符串空值快捷输出
 * @version 1.1.0 首次引入
 * @param string $value 必需;待验证的字符串
 * @param mixed $default 必需;默认值;默认：空
 * @return string $string 验证后的字符串
 */
function DcEmpty($value, $default=''){
    return !empty($value) ? $value : $default;
}
/**
 * 字符串截取
 * @version 1.0.0 首次引入
 * @param string $str 必需;待截取的字符串
 * @param int $start 必需;起始位置;默认：0
 * @param int $length 必需;截取长度;默认：空
 * @param bool $suffix 可选;超出长度是否以...显示;默认：true
 * @param string $charset 可选;字符编码;默认：utf-8
 * @return string $string 截取后的字符串
 */
function DcSubstr($str, $start=0, $length, $suffix=true, $charset="UTF-8"){
    $str = trim($str);
    if( function_exists('mb_strimwidth') ){
        if($suffix){
            return mb_strimwidth($str, $start, $length*2, '...', $charset);
        }
        return mb_strimwidth($str, $start, $length*2, '', $charset);
    }
    return @substr($str, $start, $length);
}
/**
 * XML转数组
 * @version 1.5.0 首次引入
 * @param string $xml 必需;待验证的字符串
 * @param bool $isnormal 可选;是否转义;默认：false
 * @return array $array 转换后的数组
 */
function DcXmlUnSerialize(&$xml, $isnormal = false) {
    $xml = new \net\Xml($isnormal);
    $data = $xml->parse($xml);
    return $data;
}
/**
 * 数组转XML
 * @version 1.5.0 首次引入
 * @param array $xml 必需;待转换的数组
 * @param bool $htmlon 可选;是否支持HTML标签;默认：false
 * @param bool $isnormal 可选;是否转义;默认：false
 * @param int $level 可选;待验证的字符串;默认：1
 * @return string $string 转换后的XML代码
 */
function DcXmlSerialize($arr, $htmlon = false, $isnormal = false, $level = 1) {
    $s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
    $space = str_repeat("\t", $level);
    foreach($arr as $k => $v) {
        if(!is_array($v)) {
            $s .= $space."<item id=\"$k\">".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</item>\r\n";
        } else {
            $s .= $space."<item id=\"$k\">\r\n".DcXmlSerialize($v, $htmlon, $isnormal, $level + 1).$space."</item>\r\n";
        }
    }
    $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
    return $level == 1 ? $s."</root>" : $s;
}
/**
 * Json数据序列化为字符串
 * @version 1.4.0 首次引入
 * @params array $json 必需;json数据源
 * @return string $string 序列化后的字符串
 */
function DcJsonToSerialize($json){
    if($json_array = json_decode($json, true)){
        return serialize($json_array);
    }
    return $json;
}
/**
 * 将序列化字符串转化为json
 * @version 1.4.0 首次引入
 * @params array $string 必需;序列化后的数据
 * @return string $json json格式的字符串
 */
function DcSerializeToJson($string){
    $array = unserialize($string);
    if(is_array($array)){
        return json_encode($array);
    }
    return $string;
}
/**
 * 字符串转数组
 * @version 1.1.0 首次引入
 * @param string $value 必需;待验证的字符串;默认:空
 * @return array $array 索引数组
 */
function DcStrToArray($string=''){
    if(is_string($string)){
        return [$string];
    }
    return $string;
}
/**
 * 过滤数组中空值的字段
 * @version 1.6.0 首次引入
 * @param array $array 必需;待过滤的数组;默认：空
 * @return array $array 过滤后的数组
 */
function DcArrayEmpty($array){
    return array_filter($array, function($value){
        if($value || $value=='0'){
            return true;
        }
        return false;
    });
}
/**
 * 过滤数组中不需要的字段
 * @version 1.6.0 首次引入
 * @param array $array 必需;待过滤的数组
 * @param array $fileds 必需;需保留的字段
 * @param string $prefix 可选;KEY前缀
 * @param string $suffix 可选;KEY后缀
 * @return mixed $mixed array|value 过滤后的数组
 */
function DcArrayFilter($array=[], $fileds=[], $prefix='', $suffix=''){
    $filter = [];
    foreach($array as $key=>$value){
        if(in_array($key, $fileds)){
            $filter[$prefix.$key.$suffix] = $value;
        }
    }
    return $filter;
}
/**
 * 过滤数组中没有定义的字段
 * @version 1.6.0 首次引入
 * @param array $array 必需;待过滤的数组;默认：空
 * @param array $fileds 必需;需保留的字段;默认：空
 * @return mixed $mixed array|value 过滤后的数组
 */
function DcArrayIsset($array=[], $fileds=[]){
    foreach($array as $key=>$value){
        if(!in_array($key, $fileds)){
            unset($array[$key]);
        }
    }
    foreach($fileds as $key=>$value){
        if(!isset($array[$key])){
            unset($array[$key]);
        }
    }
    return $array;
}
/**
 * 给一个数组参数或者url字符串（args）绑定默认值
 * @version 1.5.26 首次引入
 * @param array $args 必需;数组参数列表
 * @param array $defaults 可选;数组默认值;默认：空
 * @return array $array 合并后的数组
 */
function DcArrayArgs($args, $defaults = ''){
	if ( is_array( $args ) ){
		$r =& $args;
    }else{
		parse_str( $args, $r );
    }
	if ( is_array( $defaults ) ){
		return array_merge( $defaults, $r );
    }
	return $r;
}
/**
 * 将数组序列化为字符串
 * @version 1.2.0 首次引入
 * @params array $array 必需;序列化后的数据
 * @return string $json json格式的字符串
 */
function DcArraySerialize($array){
    if(is_array($array)){
        return serialize($array);
    }
    return $array;
}
/**
 * 二维数组根据字段进行排序
 * @version 1.2.0 首次引入
 * @params array $array 必需;需要排序的数组
 * @params string $field 必须;排序的字段名;默认：空
 * @params string $sort 可选;排序顺序标志，SORT_DESC＝降序，SORT_ASC＝升序;默认：SORT_DESC
 * @return array $array 排序后的数组
 */
function DcArraySequence($array, $field, $sort = 'SORT_DESC'){
    $arrSort = array();
    foreach ($array as $uniqid => $row) {
        foreach ($row as $key => $value) {
            $arrSort[$key][$uniqid] = $value;
        }
    }
    array_multisort($arrSort[$field], constant($sort), $array);
    return $array;
}
/**
 * 在数据列表中搜索
 * @version 1.2.0 首次引入
 * @param array $list 数据列表
 * @param mixed $condition 查询条件,支持array('name'=>$value)或者name=$value
 * @param string $key 要返回的字段值
 * @return mixed $mixed array|value
 */
function DcArraySearch($array, $condition, $key=''){
    $array_search = list_search($array, $condition);
    if($key){
        return $array_search[0][$key];
    }
    return $array_search;
}
/**
 * 在指定的键之前插入元素
 * @version 1.6.0 首次引入
 * @param array $array 必需;原数组;默认：空
 * @param array $data 必需;要插入的值;默认：空
 * @param string $key 可选;键名值;默认：空
 * @return array $array 合并后的值
 */
function DcArrayPush($array, $data=null, $key=false){
    $data    = (array)$data;
    $offset  = ($key===false)?false:array_search($key, array_keys($array));
    $offset  = ($offset)?$offset:false;
    if($offset){
        return array_merge(
            array_slice($array, 0, $offset), 
            $data, 
            array_slice($array, $offset)
        );
    }else{     // 没指定 $key 或者找不到，就直接加到末尾
        return array_merge($array, $data);
    }
}
/**
 * 将数据集转化为数组
 * @version 1.6.0 首次引入
 * @param mixed $data 必需;需验请的数据集;默认：空
 * @return mixed $mixed array|null
 */
function DcArrayResult($data=[]){
    if(is_null($data)){
        return null;
    }
    if(is_object($data)){
        return $data->toArray();
    }
    return $data;
}
/**
 * 判断是否为（普通/多维）数组
 * @version 1.4.0 首次引入
 * @param array $array 必需;待验证的数组;默认：空
 * @param bool $mode 可选;多维数组模式;默认：false
 * @return bool true|false 真或假
 */
function DcIsArray($array, $mode=false){
    //验证普通数组模式
    if($mode == false){
        return is_array($array);
    }
    //验证多维数组模式
    if(count($array, 1) == 1){
        return false;
    }
    //计算一维与多维是否相同，统计相同则是一维，不相同则是多维
    if (count($array) == count($array, 1)) {
        return false;
    } else {
        return true;
    }
}

/**************************************************扩展函数－无限层级分类***************************************************/
/**
 * 获取指定分类的所有子集(递归法)
 * @param array $categorys 数组列表
 * @param int $catId 主键ID值
 * @param int $level 层级记录数
 * @param int $pk 主键名称
 * @param int $pid 父级名称
 * @return array;
 */
function get_childs($categorys, $catId=0, $level=1, $pk='term_id', $pid='term_parent'){
    $subs = array();
    foreach($categorys as $item){
        if($item[$pid] == $catId){
            $item['level'] = $level;
            $subs[] = $item;
            $subs = array_merge($subs, get_childs($categorys, $item[$pk], $level+1, $pk, $pid) );
        }
    }
    return $subs;
}
/**
 * 获取某一个子类的所有父级(递归法)
 * @param array $categorys 数组列表
 * @param int $parentId 父级ID值
 * @param int $pk 主键名称
 * @param int $pid 父级名称
 * @return mixed null|array;
 */
function get_parents($categorys, $parentId, $pk='term_id', $pid='term_parent'){
    $tree = array();
    foreach($categorys as $item){
        if($item[$pk] == $parentId){
            $tree[] = $item;
            $tree = array_merge($tree, get_parents($categorys, $item[$pid], $pk, $pid) );
        }
    }
    return $tree;
}
/**
 * 将list_to_tree的树还原成带层维数的数据列表/用于表格展示
 * @param  array $tree  原来的树
 * @param  string $pkName 要添加符号的键名
 * @param  string $level 记录无限层级关系 
 * @param  string $child 孩子节点的键
 * @param  array  $list  过渡用的中间数组，
 * @return array 返回排过序的列表数组
 */
function tree_to_level($tree, $pkName='', $level=0, $child='_child', &$list = array()){
    if(is_array($tree)) {
        $icon   = '';
        if ($level > 0) {
            $icon = '|';
            for ($i=0; $i < $level; $i++) {
                //$icon .= '&nbsp;&nbsp;&nbsp;';
                $icon .= '─ ';
            }
            //$icon .= '├&nbsp;';
        }
        $refer = array();
        foreach ($tree as $value) {
            $reffer = $value;
            if($pkName){
                $reffer[$pkName] = $icon.$reffer[$pkName];
            }
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                $list[] = $reffer;
                tree_to_level($value[$child], $pkName, $level+1, $child, $list);
            }else{
                $list[] = $reffer;
            }
        }
    }
    return $list;
}
/**
 * 将原生数据集生成options的选项
 * @param array $list 原生数据
 * @param intval $pid 父级ID
 * @param intval $sid 选中ID
 * @param array $did 禁止选择
 * @param int $level 当前层数
 * @param array $config 初始配置  
 * @return string 返回格式化后的option选项
 */
function list_to_option($list = [], $pid = 0, $sid = 0, $did = [], $level = 0, $config=[]){
    $config_ = array_merge(['id'=>'op_id','pid'=>'nav_parent','name'=>'nav_text'], $config);
    $tree = new \daicuo\Tree($config_);
    return $tree->toOptions($tree->toTree($list, $pid, 0, $level), $sid);
}

/**************************************************扩展函数－驼峰**************************************************/
/**
* 下划线转驼峰
* @version 1.4.0 首次引入
* @param string $uncamelized_words 下划线样式的字符串
* @param string $separator 分隔符/默认'_'
* @return string 驼峰样式的字符串
* step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
* step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
*/
function camelize($uncamelized_words, $separator='_'){
    $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
}
/**
* 驼峰命名转下划线命名
* @version 1.4.0 首次引入
* @param string $camelCaps 驼峰命名字符串
* @param string $separator 分隔符/默认'_'
* @return string 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
*/
function uncamelize($camelCaps, $separator='_'){
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

/**************************************************扩展函数－文件与目录***************************************************/
/**
 * 读取文件
 * @param string $path 完整文件路径名
 * @return bool
 */
function read_file($path){
    $file = new \files\File();
    return $file->read($path);
}
/**
 * 写入文件
 * @param string $filename 完整文件路径名
 * @param string $data 要写入文件的内容 
 * @return bool
 */
function write_file($filename='', $data=''){
    $file = new \files\File();
    return $file->write($filename, $data);
}
/**
 * 数组保存到文件
 * @param string $filename 完整文件路径名
 * @param string $dataArray 数组
 * @return bool
 */
function write_array($filename, $dataArray=''){
    $file = new \files\File();
    return $file->write_array($filename, $dataArray);
}
/**
 * 递归创件目录
 * @param string $dirs 完整文件路径名
 * @return bool
 */
function mkdir_ss($dirs) {
    $file = new \files\File();
    return $file->d_create($dirs);
}
/**
 * 列出目录下单层所有文件夹名
 * @param string $dir 完整文件夹路径
 * @return array
 */
function glob_basename($path = 'apps/index/theme/') {
    $list = glob($path.'*');
    foreach ($list as $i=>$file){
        $dir[] = basename($file);
    }    
    return $dir;
}

/**************************************************ThinkPHP扩展函数库***************************************************/

/**
 * 判断邮箱
 * @param string $str 要验证的邮箱地址
 * @return bool
 */
function is_email($str) {
    return preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $str);
}
/**
 * 判断手机号
 * @param string $num 要验证的手机号
 * @return bool
 */
function is_mobile($num) {
    return preg_match("/^1(3|4|5|6|7|8|9)\d{9}$/", $num);
}
/**
 * 判断用户名
 * 用户名支持中文、字母、数字、下划线，但必须以中文或字母开头，长度3-20个字符
 * @param string $str 要验证的字符串
 * @return bool
 */
function is_username($str) {
    return preg_match("/^[\x80-\xffA-Za-z]{1,1}[\x80-\xff_A-Za-z0-9]{2,19}+$/", $str);
}
/**
 * 判断数据不是JSON格式
 * @param string $str 要验证的字符串
 * @return bool
 */
function is_not_json($str){  
    return is_null(json_decode($str));
}
/**
 * 在数据列表中搜索
 * @param array $list 数据列表
 * @param mixed $condition 查询条件
 * 支持 array('name'=>$value) 或者 name=$value
 * @return array
 */
function list_search($list,$condition) {
    if(is_string($condition))
        parse_str($condition,$condition);
    // 返回的结果集合
    $resultSet = array();
    foreach ($list as $key=>$data){
        $find   =   false;
        foreach ($condition as $field=>$value){
            if(isset($data[$field])) {
                if(0 === strpos($value,'/')) {
                    $find   =   preg_match($value,$data[$field]);
                }elseif($data[$field]==$value){
                    $find = true;
                }
            }
        }
        if($find)
            $resultSet[]     =   &$list[$key];
    }
    return $resultSet;
}
/**
 * 对查询结果集进行排序
 * @param array $list 查询结果
 * @param string $field 排序的字段名
 * @param string $sortby 排序类型 (asc正向排序 desc逆向排序 nat自然排序)
 * @param bool $oldkey 是否保留原key
 * @return array
 */
function list_sort_by($list, $field='', $sortby='asc', $oldkey=false) {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       foreach ($refer as $key=> $val){
           if($oldkey){
               $resultSet[$key] = &$list[$key];
           }else{
               $resultSet[] = &$list[$key];
           }
       }
       return $resultSet;
   }
   return false;
}
/*** 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array 
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root=0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}
/**
 * 将list_to_tree的树还原成列表
 * @param  array $tree  原来的树
 * @param  string $child 孩子节点的键
 * @param  string $order 排序显示的键，一般是主键 升序排列
 * @param  array  $list  过渡用的中间数组，
 * @return array 返回排过序的列表数组
 */
function tree_to_list($tree, $child = '_child', $order='id', &$list = array()){
    if(is_array($tree)) {
        $refer = array();
        foreach ($tree as $key => $value) {
            $reffer = $value;
            if(isset($reffer[$child])){
                unset($reffer[$child]);
                tree_to_list($value[$child], $child, $order, $list);
            }
            $list[] = $reffer;
        }
        $list = list_sort_by($list, $order, 'asc');
    }
    return $list;
}
/**
* XSS漏洞过滤
* @param string $val 待验证的字符串
* @return string 去掉敏感信息的字符串
*/
function remove_xss($val) {
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);
   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}
//兼容PHP低版本函数
if(!function_exists('array_column')){
    /*
     * 适用于 PHP 5.4 更早版本的 array_column() 函数
     * @param array $input 原始数组
     * @param string|integer|null $column_key 键名
     * @param string|integer $index_key 原始数组中作为结果数组键名的键名
     * @return null|array|false
    */
    function array_column($input, $column_key, $index_key=''){
        if(!is_array($input)) return;
        $results=array();
        if($column_key===null){
            if(!is_string($index_key)&&!is_int($index_key)) return false;
            foreach($input as $_v){
                    if(array_key_exists($index_key,$_v)){
                            $results[$_v[$index_key]]=$_v;
                    }
            }
            if(empty($results)) $results=$input;
        }else if(!is_string($column_key)&&!is_int($column_key)){
            return false;
        }else{
            if(!is_string($index_key)&&!is_int($index_key)) return false;                        
            if($index_key===''){
                foreach($input as $_v){
                    if(is_array($_v)&&array_key_exists($column_key,$_v)){
                        $results[]=$_v[$column_key];
                    }
                }                                
            }else{
                foreach($input as $_v){
                    if(is_array($_v)&&array_key_exists($column_key,$_v)&&array_key_exists($index_key,$_v)){
                        $results[$_v[$index_key]]=$_v[$column_key];
                    }
                }
            }

        }
        return $results;
    }
}