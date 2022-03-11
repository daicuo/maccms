<?php
namespace app\common\loglic;

//调用升级入口，每次补丁包需更新该文件

class Upgrade
{
    public function index()
    {
         return model('common/Update18','loglic')->upgrade();
    }
}