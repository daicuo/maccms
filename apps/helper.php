<?php

// 扩展函数－系统组件 用于快速调用系统默认组件数据

/**************************************************分类组件***************************************************/
/**
 * 获取导航列表简单数组格式
 * @param array $args 查询条件（一维数组）
 * @return mixed null|array;
 */
function DcCategoryOption($args){
    if($args){
        $args = array_merge(['cache'=>false], $args);
    }else{
        $args['module'] = DcHtml(input('op_module/s',''));
        $args['cache'] = false;
    }
    return \daicuo\Term::option($args);
}

/**************************************************导航组件***************************************************/
/**
 * 获取导航列表
 * @param array $args 查询条件（一维数组）
 * @return mixed null|array;
 */
function DcNavAll($args){
    return \daicuo\Nav::all($args);
}
/**
 * 获取导航列表简单数组格式
 * @param array $args 查询条件（一维数组）
 * @return mixed null|array;
 */
function DcNavOption($args){
    if(!$args){
        $args['where'] = DcWhereByQuery(['op_module','op_controll','op_action']);
        $args['cache'] = false;
    }
    return \daicuo\Nav::option($args);
}

/**************************************************权限组件***************************************************/
/**
 * 获取权限列表简单数组格式
 * @return array;
 */
function DcRolesOption(){
    $options = [];
    foreach(\daicuo\Auth::get_roles() as $role){
        $options[$role] = lang($role);
    }
    return $options;
}

/**************************************************视频组件***************************************************/
/**
 * 调用播放器
 * @param array $options  播放器参数，数组格式，
 * @return string
 */
function DcPlayer($options=[]){
    $video = new \daicuo\Video();
    return $video->player($options);
}

/**************************************************模板组件***************************************************/
/**
 * 生成模板调用配置标签
 * @param string $module 模块
 * @param string $field 字段
 * @return string;
 */
function DcTplLabelOp($module, $field){
    return DcHtml('{:config("'.$module.'.'.$field.'")}');
}

/**************************************************分页组件***************************************************/
/**
 * 解析页码为HTML
 * @param $index number 当前页
 * @param $rows number 每页数量 
 * @param $total number 总记录数
 * @param $path string URL路径
 * @param $query array URL额为参数
 * @param $fragment string URL锚点 
 * @param $varpage string 分页变量
 * @return string
 */
function DcPage($indexpage=1, $rows=10, $total=1, $path='', $query=[], $fragment='', $varpage='page'){
    $page = new \page\Bootstrap('', $rows, $indexpage, $total, false, [
        'var_page' => $varpage,
        'path' => str_replace('%5BPAGE%5D','[PAGE]',$path),//url('home/index/index','','')
        'query' => $query,
        'fragment' => $fragment,
    ]);
    return $page->render();
}
function DcPageSimple($pageIndex, $totalPage=1, $path=''){//&raquo;
    if($totalPage < 1){
        return '';
    }
    $path = str_replace('%5BPAGE%5D','[PAGE]',$path);//将转义后的方括号[page]
    if($pageIndex == 1){
        $prev = '<li class="page-item disabled"><a class="page-link" href="javascript:;">&laquo;</a></li>';
    }else{
        if(strpos($path,'[PAGE]')){
            $url = str_replace('[PAGE]',($pageIndex-1),$path);
        }else{
            $url = $path.($pageIndex-1);
        }
        $prev = '<li class="page-item"><a class="page-link" href="' . htmlentities($url) . '">&laquo;</a></li>';
    }
    if($pageIndex < $totalPage){
        if(strpos($path,'[PAGE]')){
            $url = str_replace('[PAGE]',($pageIndex+1),$path);
        }else{
            $url = $path.($pageIndex);
        }
        $next = '<li class="page-item"><a class="page-link" href="' . htmlentities($url) . '">&raquo;</a></li>';
    }else{
        $next = '<li class="page-item disabled"><a class="page-link" href="javascript:;">&raquo;</a></li>';
    }
    return sprintf('<ul class="pagination pagination-lg">%s %s</ul>', $prev, $next);
}