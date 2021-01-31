<?php
namespace app\api\controller;

use app\common\controller\Api;

class Upload extends Api
{
    // 权限认证
    protected $auth = [
         'check'       => true,
         'none_login'  => [],
         'none_right'  => [],
         'error_login' => 'api/token/index',
         'error_right' => '',
    ];
    
    // 初始化
	public function _initialize(){
		parent::_initialize();
	}
    
    // 添加附件到服务器
    public function save()
    {
        $result = \daicuo\Upload::save_all($this->request->file('file'));
        return json($result);
    }
    
    // 删除附件从服务器
	public function delete()
    {
	    $ids = input('id/a');
		if(!$ids){
			$this->error(lang('mustIn'));
		}
        foreach($ids as $id){
            \daicuo\Upload::delete($id);
        }
        $this->success(lang('success'));
	}
}