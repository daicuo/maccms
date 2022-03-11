<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\db\connector;

use PDO;
use think\db\Connection;

/**
 * Sqlite数据库驱动
 */
class Sqlite extends Connection
{

    protected $builder = '\\think\\db\\builder\\Sqlite';

    /**
     * 解析pdo连接的dsn信息
     * @access protected
     * @param array $config 连接信息
     * @return string
     */
    protected function parseDsn($config)
    {
        $dsn = 'sqlite:' . $config['database'];
        return $dsn;
    }

    /**
     * 取得数据表的字段信息
     * @access public
     * @param string $tableName
     * @return array
     */
    public function getFields($tableName)
    {
        list($tableName) = explode(' ', $tableName);
        $sql             = 'PRAGMA table_info( ' . $tableName . ' )';

        $pdo    = $this->query($sql, [], false, true);
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        if ($result) {
            foreach ($result as $key => $val) {
                $val                = array_change_key_case($val);
                $info[$val['name']] = [
                    'name'    => $val['name'],
                    //'type'    => $val['type'],
                    'type'    => $this->fieldsType($val['type']),
                    'notnull' => 1 === $val['notnull'],
                    'default' => $val['dflt_value'],
                    'primary' => '1' == $val['pk'],
                    'autoinc' => '1' == $val['pk'],
                ];
            }
        }
        return $this->fieldCase($info);
    }
    
    /**
     * 特殊类型转换为SQLITE常用字段（TP自动绑定时获取的类型）
     * @param string $type 
     * @return string
     */
    private function fieldsType($type=''){
        if(preg_match('/(int|double|float|decimal|real|numeric|serial|bit)/is', $type)){
            return 'TEXT';
        }
        return $type;
    }

    /**
     * 取得数据库的表信息
     * @access public
     * @param string $dbName
     * @return array
     */
    public function getTables($dbName = '')
    {

        $sql = "SELECT name FROM sqlite_master WHERE type='table' "
            . "UNION ALL SELECT name FROM sqlite_temp_master "
            . "WHERE type='table' ORDER BY name";

        $pdo    = $this->query($sql, [], false, true);
        $result = $pdo->fetchAll(PDO::FETCH_ASSOC);
        $info   = [];
        foreach ($result as $key => $val) {
            $info[$key] = current($val);
        }
        return $info;
    }

    /**
     * SQL性能分析
     * @access protected
     * @param string $sql
     * @return array
     */
    protected function getExplain($sql)
    {
        return [];
    }

    protected function supportSavepoint()
    {
        return true;
    }
}
