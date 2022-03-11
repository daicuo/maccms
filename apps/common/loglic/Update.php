<?php
namespace app\common\loglic;

use think\Db;

class Update
{
    // 错误信息
    protected $error = '';
    
    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }
    
    //执行SQL语句
    protected function executeSql($sql){
        foreach($sql as $key=>$value){
            Db::execute($value); 
            dump('执行语句 >> '.$value);
        }
    }
    
    //获取所有数据表信息
    protected function tablesInfo()
    {
        $tables = [];
        
        //mysql数据库结构
        if(config('database.type') == 'mysql'){
            //$tables  = Db::query("show tables");
            foreach(Db::query("SHOW TABLE STATUS") as $key=>$table){
                //表字段
                $tables['fields'][$table['Name']] = $this->getTableInfo($table['Name'],'fields');
                //表索引
                $index = Db::query("show index from ".$table['Name']);
                $tables['index'][$table['Name']] = array_column($index, 'Key_name');
            }
            return $tables;
        }
        
        //sqlite3数据库结构
        //dump(Db::query("select * from sqlite_master"));
        //$tables = Db::getTables();
        foreach(Db::query("select * from sqlite_master where type='table'") as $key=>$table){
            if($table['name'] == 'sqlite_sequence'){
                continue;
            }
            $tables['fields'][$table['name']] = $this->getTableInfo($table['name'],'fields');
        }
        //表索引
        foreach(Db::query("select * from sqlite_master where type = 'index'") as $key=>$table){
            $tables['index'][$table['tbl_name']][] = $table['name'];
        }
        return $tables;
    }
    
    /**
     * 获取单个数据表信息
     * @access public
     * @param mixed  $tableName 数据表名 留空自动获取
     * @param string $fetch 获取信息类型 包括 fields type
     * @return mixed
     */
    protected function getTableInfo($tableName = '', $fetch = '')
    {
        $info   = [];

        //mysql数据库结构
        if(config('database.type') == 'mysql'){
            $result = Db::query('SHOW COLUMNS FROM ' . $tableName);
            foreach ($result as $key => $val) {
                $val                 = array_change_key_case($val);
                $info[$val['field']] = [
                    'name'    => $val['field'],
                    'type'    => $val['type'],
                    'notnull' => (bool) ('' === $val['null']), // not null is empty, null is yes
                    'default' => $val['default'],
                    'primary' => (strtolower($val['key']) == 'pri'),
                    'autoinc' => (strtolower($val['extra']) == 'auto_increment'),
                ];
            }
        }else{
            //sqlite3数据库结构
            $result = Db::query("PRAGMA table_info( $tableName )");
            foreach ($result as $key => $val) {
                $val                = array_change_key_case($val);
                $info[$val['name']] = [
                    'name'    => $val['name'],
                    'type'    => $val['type'],
                    'notnull' => 1 === $val['notnull'],
                    'default' => $val['dflt_value'],
                    'primary' => '1' == $val['pk'],
                    'autoinc' => '1' == $val['pk'],
                ];
            }
        }

        //返回数据
        if($fetch == 'fields'){
            return array_keys($info);
        }else if($fetch == 'type'){
            $type = [];
            foreach ($info as $key => $val) {
                $type[$key] = $val['type'];
            }
            return $type;
        }
        return $info;
    }
    
    //清空缓存
    protected function configClear()
    {
        $file = new \files\File();
        
        $file->d_delete(LOG_PATH);
        
        $file->d_delete(CACHE_PATH);
        
        $file->d_delete(TEMP_PATH);
    }
}