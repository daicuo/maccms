<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Update extends Admin
{
    //手动升级
    public function index()
    {
        model('common/Upgrade','loglic')->index();
        
        $this->success(lang('success'), 'index/index');
    }
    
    //在线一键升级
    public function online()
    {
        //实例化文件类
        $file = new \files\File();
        //写入权限检查
        if(!$file->is_write('./temp')){
            $this->error( lang('web_create_failed') );
        }
        $file->d_delete('./temp');
        
        //下载补丁包到临时目录（会根据当前版本号自动返回对应的升级包，依次升级）
        $service = new \daicuo\Service();
        if(!$saveFile = $service->applyDownLoad(['event'=>'update','module'=>'daicuo','version'=>config('daicuo.version')])){
            $this->error( lang($service->getError()) );
        }

        //在线解压到根目录
        $zip = new \files\Zip();
        if(!$zip->unzip($saveFile, './')){
            $file->f_delete($saveFile);
            $this->error( lang('web_unzip_failed') );
        }
        
        //执行SQL脚本
        model('common/Upgrade','loglic')->index();
        
        //删除压缩包
        $file->f_delete($saveFile);
        
        //返回结果
        $this->success(lang('success'), 'index/index');
    }
}