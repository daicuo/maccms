<?php
// 返回本地应用列表
function DcAdminApply(){
	$dirs = array();
	foreach(glob(APP_PATH.'*',GLOB_ONLYDIR) as $key=>$value){
		if(!in_array(basename($value),array('admin','api','common','extra','lang'))){
			array_push($dirs, basename($value));
		}
	}
	return $dirs;
}
//菜单标题
function adminMenuName($value=''){
    return DcSubstr(DcHtml(lang($value)),0,6,false);
}
//菜单链接
function adminMenuUrl($termSlug=''){
    if(!$termSlug){
        return 'javascript:;';
    }
    if($termSlug=='../../'){
        return $termSlug;
    }
    //分解地址栏参数
    $array = parse_url($termSlug);
    //外部链接
    if($array['scheme']){
        return $termSlug;
    }
    //内部链接
    $path = explode('/',$array['path']);
    //默认后台
    if($path[0]=='admin'){
        return DcUrl($array['path'],$array['query']);
    }
    //插件后台
    parse_str($array['query'],$jump);
    $jump = array_merge([
        'module'   => $path[0],
        'controll' => $path[1],
        'action'   => $path[2],
    ],$jump);
    return DcUrlAddon($jump);
}
//判断一级菜单展示面板
function adminMenuShow($active='', $slug='', $child=[]){
    //优先地址栏parent
    if(adminMenuParent($active) == $slug){
        return 'show';
    }
    //无parent时搜索子元素是否有相同
    if(DcArraySearch($child,['term_slug'=>$active],'term_id')){
        return 'show';
    }
    return '';
}
//菜单高亮颜色
function adminMenuColor($active='', $slug=''){
    if($active == $slug){
        return 'text-purple';
    }
    if(adminMenuActive($active) == $slug){
        return 'text-purple';
    }
    return 'text-muted';
}
//获取地址栏父级
function adminMenuParent($url=''){
    $url = parse_url($url);
    parse_str($url['query'], $query);
    return $query['parent'];
}
//高亮地址解析
function adminMenuActive($url=''){
    $url = parse_url($url);
    if(!$url['query']){
        return $url['path'];
    }
    parse_str($url['query'], $query);
    unset($query['module']);
    unset($query['controll']);
    unset($query['action']);
    return $url['path'].'?'.http_build_query($query);
}