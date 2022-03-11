<?php
namespace app\maccms\event;

class Sql
{
    /**
    * 安装时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
	public function install()
    {
        //添加配置
        model('maccms/Datas')->insertConfig();
        
        //添加路由
        model('maccms/Datas')->insertRoute();
        
        //扩展字段
        model('maccms/Datas')->insertField();
        
        //后台菜单
        model('maccms/Datas')->insertMenu();
        
        //添加分类
        model('maccms/Datas')->insertCategory();
        
        //清空缓存
        \think\Cache::clear();
        
        return true;
	}
    
    /**
    * 升级时触发/通常用于数据库操作
    * @return bool 只有返回true时才会往下执行
    */
    public function upgrade()
    {
        //是否已经升级
        if(config('common.apply_version') == '1.5.1'){
            return true;
        }
        
        //需要转换分类与导航信息1.8.46
        model('maccms/Upgrade')->init();
        
        //扩展字段
        model('maccms/Datas')->insertField();
        
        //后台菜单
        model('maccms/Datas')->insertMenu();
        
        //更新MACCMS基础信息
        \daicuo\Apply::updateStatus('maccms', 'enable');
        
        //更新应用打包配置
        \daicuo\Op::write([
            'apply_version' => '1.5.1',
            'apply_rely'    => '',
        ]);
        
        //清空缓存
        \think\Cache::clear();
        
        return true;
	}
    
    /**
    * 卸载插件时触发
    * @return bool 只有返回true时才会往下执行
    */
    public function remove()
    {
        return $this->unInstall();
    }
    
    /**
    * 删除插件时触发
    * @return bool 只有返回true时才会往下执行
    */
    public function unInstall()
    {
        //删除插件配置
        \daicuo\Op::delete_module('maccms');
        
        //删除插件分类
        \daicuo\Term::delete_module('maccms');
        
        return true;
	}
	
}