<?php
namespace app\admin\controller;

use app\common\controller\Admin;

class Tool extends Admin
{

	public function index()
    {
        return $this->fetch();
	}
    
    //清空缓存
    public function clear_cache(){
        \think\Cache::clear();
        //压缩数据库
		if(config('cache.type') == 'Sqlite3'){
			\think\Db::connect(['type'=>'sqlite','database'=>config('cache.db')])->execute('VACUUM sharedmemory');
		}
		$this->success(lang('success'));
    }
    
    //清空临时文件
	public function clear_runtime()
    {
        $file = new \files\File();
        //删除应用日志目录
        $file->d_delete(LOG_PATH);
        //删除模板缓存目录
        $file->d_delete(CACHE_PATH);
        //删除应用缓存目录
        $file->d_delete(TEMP_PATH);
		$this->success(lang('success'));
	}
    
	//清空配置
	public function clear_option()
    {
        \daicuo\Op::delete_all(['op_id'=>['gt',1]]);
        \think\Cache::clear();
        $this->success(lang('success'));
	}
	
}
