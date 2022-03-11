<?php
namespace app\admin\controller;

use app\admin\controller\Admin;

class Error extends Admin
{
    
    public function create()
    {
        return 'index';
    }
    
    public function edit()
    {
        return 'edit';
    }
    
    public function index()
    {
        return 'index';
    }
    
	public function _empty($name)
    {
        return $name;
	}
    
}