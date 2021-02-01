<?php
namespace app\database\event;

use think\Controller;

class Execute extends Controller
{
	
	public function _initialize(){
		parent::_initialize();
	}

	public function index(){
        return $this->fetch('database@execute/index');
	}
    
    public function update(){
        $sql = input('post.database_sql')."\n";
        $sqlArray = explode(';n', str_replace(array(";\r\n", ";\r", ";\n"), ";n", $sql));
        foreach($sqlArray as $sql){
            if( trim($sql) ){
                \think\Db::execute(trim($sql));
            }
        }
        $this->success(lang('success'));
	}
}