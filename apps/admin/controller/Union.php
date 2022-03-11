<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Union extends Admin
{
    /**
    * 申请token
    * @return mixed
    */
    public function index()
    {
        $service = new \daicuo\Service();
        
        $this->redirect($service->apiUrl().'/token/?'.http_build_query($this->query),302);
    }
}
