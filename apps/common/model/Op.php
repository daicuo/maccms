<?php
namespace app\common\model;

use think\Model;

class Op extends Model{

    //获取器不存在的字段
    protected $append = ['op_status_text'];

    //修改器
	public function setOpValueAttr($value, $data)
    {
        if( is_array($value) ){
            return serialize($value);
        }
        return $value;
    }

    //获取器
    public function getOpValueAttr($value, $data)
    {
        if($array = unserialize($value)){
            return $array;
        }
        return $value;
    }
    
    //获取器增加不存在的字段
    public function getOpStatusTextAttr($value, $data)
    {
        $status = ['normal'=>lang('normal'),'hidden'=>lang('hidden'),'private'=>lang('private'),'public'=>lang('public')];
        return $status[$data['op_status']];
    }
    
	//get|find|save|saveAll|update|getBy***
    /*	
        // 定义时间戳字段名
        protected $createTime = 'create_at';
        protected $updateTime = 'update_at';
        // 关闭自动写入update_time字段
        protected $updateTime = false;
        // 关闭自动写入时间戳
        protected $autoWriteTimestamp = false;
        // 开启自动写入时间戳
        protected $autoWriteTimestamp = true;//datetime|int|timestamp

        //获取器（查询后处理）get/all/find/select model->op_order model->toArray() model->getData();
        public function getOpOrderAttr($value)
        {
            $array = [-1=>'删除',0=>'禁用',1=>'正常',2=>'待审核'];
            return $array[$value];
        }

        //修改器（写入前处理）save/saveAll方法才有效;
        public function setOpNameAttr($value,$data)
        {
            return strtolower($value);
            //return serialize($data);
        }	
    */
	
}