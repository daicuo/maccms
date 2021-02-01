<?php
namespace app\database\event;

use think\Controller;

use app\database\logic\Database as dbOper;

use think\Db;

class Import extends Controller
{
	// 初始化
	public function _initialize()
    {
    
        $this->request->filter('trim,strip_tags,htmlspecialchars');
        
		parent::_initialize();
	}
    
    // 还原至数据库
    public function update()
    {
        $id = input('request.id/s');
        
        if (empty($id)) {
            return $this->error(lang('mustIn'));
        }
        
        $data = $this->filesList( config('database_backup_path') ) ;
        
        if($data[$id]){
        
            $max = $data[$id]['count'];
            
            $extension = DcDefault($data[$id]['extension'],'sql','.sql','.sql.gz');
            
            for($i=1; $i<=$max; $i++){
                
                //备份配置
                $config = array(
                    'compress' => config('database_backup_compress'),//是否压缩
                );
                
                //待还原文件
                $file = [
                    'name' => config('database_backup_path').$data[$id]['name'].'-'.$i.$extension,
                ];

                $database = new dbOper($file, $config);
                
                $start = $database->import(0);

                // 导入所有数据
                while (0 !== $start) {

                    if (false === $start) {
                        return $this->error(lang('error'));
                    }

                    $start = $database->import($start[0]);
                }
            }
            
            return $this->error(lang('success'));
        }
        
        return $this->error(lang('error'));
    }
    
    // 删除备份文件
    public function delete()
    {
        $id = input('request.id/s');
        
        if (empty($id)) {
            return $this->error(lang('mustIn'));
        }
        
        $data = $this->filesList( config('database_backup_path') ) ;
        
        if($data[$id]){
        
            $file = new \files\File();
            
            $max = $data[$id]['count'];
            
            $extension = DcDefault($data[$id]['extension'],'sql','.sql','.sql.gz');
            
            for($i=1;$i<=$max;$i++){
            
                $filePath = config('database_backup_path').$data[$id]['name'].'-'.$i.$extension;
                
                $file->f_delete($filePath);
            }
        }
        
        return $this->success(lang('success'));
    }
    
    // 展示备份文件
	public function index()
    {
    
        if (!is_dir(config('database_backup_path'))) {
            return $this->error(lang('import_not_found'));
        }
        
        if($this->request->isAjax()){
            $data = array_values( $this->filesList( config('database_backup_path') ) );
            return json($data);
        }

        return $this->fetch('database@import/index');
	}
    
    // 格式化数据库备份目录文件列表（key为备份名）
    private function filesList($path)
    {
        $dir = new \files\Dir();
        
        $list = $dir->listFile(config('database_backup_path'));
        
        $data = [];
        
        foreach ($list as $key => $file) {
        
            preg_match("/([0-9]{8})-([0-9]{6})-([0-9]+)\.sql(?:\.gz)?$/", $file["basename"], $array);
            
            if($array){
                $data[$array[2]][$array[3]]['name'] = $array[1].'-'.$array[2];
                $data[$array[2]][$array[3]]['size'] = $file['size'];
                $data[$array[2]][$array[3]]['extension'] = $file['extension'];
                $data[$array[2]][$array[3]]['ctime'] = $file['ctime'];
            }
        }
        
        $item = [];
        foreach($data as $key=>$value){
            //$item[$key] = $value[1];
            $item[$key]['name'] = $value[1]['name'];
            $item[$key]['extension'] = $value[1]['extension'];
            $item[$key]['ctime'] = date('Y-m-d H:i:s',$value[1]['ctime']);
            $item[$key]['size'] = round($value[1]['size']/1024, 0).'KB';
            $item[$key]['id'] = $key;
            $item[$key]['count'] = count($value);
        }
        
        return $item;
    }
}