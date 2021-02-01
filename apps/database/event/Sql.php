<?php
namespace app\database\event;

class Sql
{
    /**
    * 安装时触发/通常用于数据库操作或调用接口
    * @return bool 只有返回true时才会往下执行
    */
	public function install()
    {
        return true;
	}
    
    /**
    * 升级时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
    public function upgrade()
    {
        return true;
    }
    
    /**
    * 卸载时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
    public function unInstall()
    {
        return true;
	}
	
}