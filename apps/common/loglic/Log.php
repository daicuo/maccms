<?php
namespace app\common\loglic;

class Log
{
    protected $error = '';
    
    //获取错误信息
    public function getError(){
        return $this->error;
    }
    
    /**
     * 自动获取表单字段基本格式
     * @param array $where 删除条件
     * @return array 基础格式的表单字段
     */
    public function fields($data)
    {
        $fields = DcFields('log', $data);
        //对字段做表单与表格格式化修改
        foreach($fields as $key=>$value){
            $fields[$key]['placeholder'] = '';
            if(in_array($key,['log_id','log_user_id','log_info_id','log_value','log_decimal','log_create_time'])){
                $fields[$key]['data-sortable'] = true;
            }
            if(in_array($key,['log_info'])){
                $fields[$key]['data-visible'] = false;
                $fields[$key]['type'] = 'textarea';
                $fields[$key]['rows'] = '3';
            }
            if(!in_array($key,['log_user_id','log_info_id','log_ststus','log_module','log_controll','log_action','log_type','log_ip'])){
                $fields[$key]['data-filter'] = false;
            }
            if(in_array($key,['log_id'])){
                $fields[$key]['data-width'] = 80;
            }
        }
        return $fields;
    }
     
    /**
     * 创建一条记录
     * @param array $where 删除条件
     * @return int 返回操作记录
     */
    public function save($data)
    {
        return DcDbSave('common/Log', $data);
    }
    
    /**
     * 按条件删除一条记录
     * @param array $where 删除条件
     * @return int 返回操作记录
     */
    public function delete($where)
    {
        return DcDbDelete('common/Log', $where);
    }
    
    /**
     * 修改一条日志
     * @param array $where 修改条件
     * @param array $data 写入数据（一维数组） 
     * @return mixed array|null
     */
    public function update($where, $data)
    {
        unset($data['log_create_time']);
        return DcArrayResult( DcDbUpdate('common/Log', $where, $data) );
    }
    
    /**
     * 按条件查询一条日志
     * @param array $args 查询参数
     * @return mixed array|null
     */
    public function get($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //初始参数
        $args = DcArrayArgs($args, [
            'cache'     => true,
            'field'     => '',
            'fetchSql'  => false,
            'where'     => '',
            'with'      => '',//log_user,log_info
        ]);
        //返回结果
        return DcArrayResult( DcDbFind('common/Log', $args) );
    }
    
    /**
     * 按条件查询多条日志
     * @param array $args 查询条件（一维数组）
     * @return mixed array|null
     */
    public function all($args)
    {
        //格式验证
        if(!is_array($args)){
            return null;
        }
        //分页处理
        if($args['limit'] && $args['page']){
            $args['paginate'] = [
                'list_rows' => $args['limit'],
                'page' => $args['page'],
            ];
            unset($args['limit']);
            unset($args['page']);
        }
        //参数初始化
        $args = array_merge([
            'cache'     => true,
            'field'     => '',
            'fetchSql'  => false,
            'sort'      => 'log_id',
            'order'     => 'desc',
            'paginate'  => '',
            'where'     => '',
            'with'      => '',//log_user,log_info
        ], $args);
        //查询数据
        return DcArrayResult( DcDbSelect('common/Log', $args) );
    }
}