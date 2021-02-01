<?php
namespace app\database\event;

use think\Controller;

use app\database\logic\Database as dbOper;

use think\Db;

class Admin extends Controller
{
	// 初始化
	public function _initialize()
    {
    
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        
		parent::_initialize();
	}
    
    // 备份数据库
    public function export()
    {
        $start = 0;
        
        $tables = input('request.id/a');
        
        if (empty($tables)) {
            return $this->error(lang('mustIn'));
        }
        
        //备份配置
        $config = array(
            'path'     => config('database_backup_path'),//备份路径
            'part'     => config('database_backup_size'),//分段大小
            'compress' => config('database_backup_compress'),//是否压缩
            'level'    => config('database_backup_level'),//压缩级别
        );
        
        //创建备份目录
        if (!is_dir($config['path'])) {
            \files\Dir::create($config['path'], 0755, true);
        }
        
        //生成备份文件信息
        $file = [
            'name' => date('Ymd-His', $this->request->time()),
            'part' => 1,
        ];
        
        //创建备份文件
        $database = new dbOper($file, $config);

        //是否有写入文件权限
        if($database->create() !== false) {
        
            // 备份指定表
            foreach ($tables as $table) {
                $start = $database->backup($table, $start);
                while (0 !== $start) {
                    if (false === $start) {
                        return $this->error(lang('error'));
                    }
                    $start = $database->backup($table, $start[0]);
                }
            }
            
            return $this->success(lang('success'));
        }
        
        return $this->success(lang('error'));
    }
    
    // 优化数据表
    public function optimize()
    {
        $id = input('request.id/a');
        
        if (empty($id)) {
            return $this->error(lang('mustIn'));
        }

        $tables = implode('`,`', $id);
        
        $res = Db::query("OPTIMIZE TABLE `{$tables}`");
        
        if ($res) {
            return $this->success(lang('success'));
        }

        return $this->error(lang('error'));
    }
    
    // 修复数据表
    public function repair()
    {
        $id = input('request.id/a');
        
        if (empty($id)) {
            return $this->error(lang('mustIn'));
        }

        $tables = implode('`,`', $id);
        
        $res = Db::query("REPAIR TABLE `{$tables}`");
        
        if ($res) {
            return $this->success(lang('success'));
        }

        return $this->error(lang('error'));
    }
    
    // 数据库管理
	public function index()
    {
        if( config('database.type') != 'mysql'){
            return $this->error(lang('database_not_mysql'));
        }
    
        $tables = Db::query("SHOW TABLE STATUS");
        
        foreach ($tables as $k => &$v) {
            $v['id'] = $v['Name'];
        }
        
        if($this->request->isAjax()){
            return json($tables);
        }
        
        return $this->fetch('database@admin/index');
	}
}