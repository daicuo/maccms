<?php
namespace app\common\loglic;

use app\common\loglic\Update;

class Update18 extends Update
{
    private $version = '1.8.0';
    
    public function upgrade()
    {
        $this->version = config('daicuo.version');
        
        if( is_file('./datas/db/'.$this->version.'.lock') ){
			$this->error = '已经是最新版，建议删除该文件（./apps/common/loglic/Update18.php）';
            return false;
		}
        
        //升级表结构	
		if(config('database.type') == 'sqlite'){
            $this->_sqlite();
        }else{
            $this->_mysql();
        }
        
        //增加锁文件
        touch('./datas/db/'.$this->version.'.lock');
        
        return true;
    }
    
    //mysql脚本
    private function _mysql()
    {
        
        $this->sqlCommon();
    }
    
    //sqlite3数据库
    private function _sqlite()
    {
        $this->sqlCommon();
    }
    
    //共用sql
    private function sqlCommon()
    {
        //定义变量
        $sql = [];
        
        array_push($sql, "update dc_op set op_controll='config' where op_autoload='yes';");
        
        array_push($sql, "update dc_term set term_controll='category' where term_type='category';");
        
        array_push($sql, "update dc_term set term_controll='tag' where term_type='tag';");
        
        //执行SQL语句
        $this->executeSql($sql);
        
        //安装语言包
        model('common/Lang','loglic')->unInstall('admin');
        model('admin/Datas','loglic')->defaultLang();
        
        //安装用户组
        model('common/Role','loglic')->unInstall('admin');
        model('admin/Datas','loglic')->defaultRole();
        
        //安装初始权限
        model('common/Auth','loglic')->unInstall('admin');
        model('admin/Datas','loglic')->defaultAuth();
        
        //安装后台菜单
        \daicuo\Term::delete_all(['term_controll' => 'menus']);
        model('admin/Datas','loglic')->defaultMenu();
        
        //更新首页插件
        //controller('index/Sql', 'event')->upgrade();
        
        //更新缓存
        \think\Cache::clear();
    }
}