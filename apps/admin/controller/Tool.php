<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Tool extends Admin
{
    //删除相关
    public function delete()
    {
        if($this->query['type'] == 'cache'){
             $this->clearCache();
        }elseif($this->query['type'] == 'runtime'){
             $this->clearRuntime();
        }
        $this->success(lang('fail'));
    }
    
    //清空配置
	public function clear()
    {
        //删除所有配置表
        \daicuo\Op::delete_all(['op_id'=>['gt',0]]);
        
        //安装初始配置
        model('admin/Datas','loglic')->defaultConfig();
        
        //安装语言包
        model('admin/Datas','loglic')->defaultLang();
        
        //安装用户组
        model('admin/Datas','loglic')->defaultRole();
        
        //安装初始权限
        model('admin/Datas','loglic')->defaultAuth();
        
        //安装后台菜单
        \daicuo\Term::delete_all(['term_controll' => 'menus']);
        model('admin/Datas','loglic')->defaultMenu();
        
        //更新缓存
        \think\Cache::clear();
        
        $this->success(lang('success'));
	}
    
    //更新相关
    public function update()
    {
        if($this->query['type'] == 'termCount'){
             $this->termCount();
        }
        $this->success(lang('fail'));
    }
    
    //清空缓存
    private function clearCache()
    {
        \think\Cache::clear();
        //压缩数据库
		if(config('cache.type') == 'Sqlite3'){
			\think\Db::connect(['type'=>'sqlite','database'=>config('cache.db')])->execute('VACUUM sharedmemory');
		}
		$this->success(lang('success'));
    }
    
    //清空临时文件
	private function clearRuntime()
    {
        $file = new \files\File();
        //删除应用日志目录
        $file->d_delete(LOG_PATH);
        //删除模板缓存目录
        $file->d_delete(CACHE_PATH);
        //删除应用缓存目录
        $file->d_delete(TEMP_PATH);
        //返回结果
		$this->success(lang('success'));
	}
    
    // 更新标签与栏目统计
    private function termCount()
    {
        //分组查询统计
        $item = db('term_map')
        ->field('term_id,count(term_id) as term_count')
        ->where('term_id > 0')
        ->group('term_id')
        ->order('term_count desc')
        ->select();
        
        //空数据集
        if($item->isEmpty()){
            $this->error(lang('fail'));
        }
        
        //批量更新
        dbUpdateAll('term', $item->toArray());
        
        $this->success(lang('success'));
    }
}
