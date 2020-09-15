<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="format-detection" content="telephone=no"/>{block name="header_meta"}
<title>DaiCuo</title>{/block}<!-- fonts -->
<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
<!-- jquery -->
<script src="//lib.baomitu.com/jquery/3.3.1/jquery.min.js"></script>
<!-- bootsrtap -->
<link href="//lib.baomitu.com/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="//lib.baomitu.com/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>
<!-- 扩展 -->{block name="header_more"}{/block}
<!-- base.css -->
<link href="{$path_root}public/static/base.css" rel="stylesheet">
<!-- base.js -->  
<script src="{$path_root}public/static/base.js" data-id="daicuo" data-file="{$file}" data-root="{$path_root}" data-view="{$path_view}" data-module="{$module}" data-controll="{$controll}" data-action="{$action}" data-page="{$page}" data-userId="{$user.user_id|default=0}"></script>
<!--语言包 -->    
<script src="{$path_root}public/static/{:config('default_lang')}.js"></script>
<!-- 后台 -->
<link href="{$path_root}{$path_view}theme.css" rel="stylesheet">
<script src="{$path_root}{$path_view}theme.js"></script>
<!-- 插件 -->{block name="header_addon"}{/block}
</head>
<body class="bg-light">
<!-- -->
<div class="modal fade dc-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document"></div>
</div>
<!-- -->
<nav class="navbar navbar-expand-md navbar-dark bg-purple fixed-top">
    <a class="navbar-brand d-none d-md-inline" href="{:DcUrl('admin/index/index','','')}">
        {:DcEmpty(config('common.apply_name'),lang('appName'))}
    </a>
    <div class="w-100 d-flex justify-content-between justify-content-md-end">
    <button class="navbar-toggler" type="button" data-toggle="main-left">
        <i class="fa fa-navicon text-light"></i>
    </button>
    <a class="navbar-text text-light" href="{:DcUrl('admin/index/index','','')}"><i class="fa fa-gear"></i>{:lang('dcAdmin')}</a>
    <a class="navbar-text text-light ml-md-3" href="{$path_root}" target="_blank"><i class="fa fa-home"></i>{:lang('dcHome')}</a>
    <a class="navbar-text text-light ml-md-3" href="{:DcUrl('admin/index/logout','','')}"><i class="fa fa-sign-out"></i>{:lang('logout')}</a>
    </div>
</nav>
<!-- -->
<div class="container-fluid">
<div class="row">
    <div class="main-left col-4 col-md-2 col-xl-1 pr-0 pl-1 d-none d-md-block">
        <div class="sidebar scrollbar pt-2">
            <ul class="nav flex-column" id="menu-parent">
                <!---->
                <li class="nav-item mb-2 pl-md-2">
                <h6>
                    <i class="fa fa-fw fa-gears"></i>
                    <a class="text-dark" href="#menu-option" data-toggle="collapse">{:lang('menuConfig')}</a>
                </h6>
                <ul class="list-unstyled collapse pl-2 {in name="controll" value="op,cache,video"}show{/in}" id="menu-option" data-parent="#menu-parent">
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-gear"></i>
                        <a class="text-{:DcDefault($controll.$action,'opindex','purple','muted')}" href="{:DcUrl('admin/op/index','','')}">{:lang('opIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-folder"></i>
                        <a class="text-{:DcDefault($controll.$action,'cacheindex','purple','muted')}" href="{:DcUrl('admin/cache/index','','')}">{:lang('cacheIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-file-movie-o"></i>
                        <a class="text-{:DcDefault($controll.$action,'videoindex','purple','muted')}" href="{:DcUrl('admin/video/index','','')}">{:lang('videoIndex')}</a>
                    </li>
                </ul>
            </li>
            <!---->
            <li class="nav-item mb-2 pl-md-2">
                <h6>
                    <i class="fa fa-fw fa-dashboard"></i>
                    <a class="text-dark" href="#menu-system" data-toggle="collapse">{:lang('menuSystem')}</a>
                </h6>
                <ul class="list-unstyled collapse pl-2 {in name="controll" value="tool,route,hook,nav,term,category,tag,user,index"}show{/in}" id="menu-system" data-parent="#menu-parent">
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-wrench"></i>
                        <a class="text-{:DcDefault($controll.$action,'toolindex','purple','muted')}" href="{:DcUrl('admin/tool/index','','')}">{:lang('toolIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-cogs"></i>
                        <a class="text-{:DcDefault($controll.$action,'routeindex','purple','muted')}" href="{:DcUrl('admin/route/index','','')}">{:lang('routeIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-anchor"></i>
                        <a class="text-{:DcDefault($controll.$action,'hookindex','purple','muted')}" href="{:DcUrl('admin/hook/index','','')}">{:lang('hookIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-navicon"></i>
                        <a class="text-{:DcDefault($controll.$action,'navindex','purple','muted')}" href="{:DcUrl('admin/nav/index','','')}">{:lang('navIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-leaf"></i>
                        <a class="text-{:DcDefault($controll.$action,'categoryindex','purple','muted')}" href="{:DcUrl('admin/category/index','','')}">{:lang('categoryIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-tag"></i>
                        <a class="text-{:DcDefault($controll.$action,'tagindex','purple','muted')}" href="{:DcUrl('admin/tag/index','','')}">{:lang('tagIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-user"></i>
                        <a class="text-{:DcDefault($controll.$action,'userindex','purple','muted')}" href="{:DcUrl('admin/user/index','','')}">{:lang('userIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-clone"></i>
                        <a class="text-{:DcDefault($controll.$action,'indexindex','purple','muted')}" href="{:DcUrl('admin/index/index','','')}">{:lang('indexIndex')}</a>
                    </li>
                </ul>
            </li>
            <!---->
            {volist name=":config('common.site_applys')" id="apply"}
                <li class="nav-item mb-2 pl-md-2">
                    <h6>
                        <i class="fa fa-fw {$apply.ico|DcHtml|default='fa-plus'}"></i>
                        <a class="text-dark" href="#menu-{$apply.module}" data-toggle="collapse">{$apply.nav|DcHtml|DcSubstr=0,6,false}</a>
                    </h6>
                    <ul class="list-unstyled collapse pl-2 {:DcDefault(input('module'),$apply['module'],'show')}" id="menu-{$apply.module}" data-parent="#menu-parent">
                        {volist name="apply.subnav" id="subnav"}
                        <li class="list-item mb-2">
                            <i class="fa fa-fw {$apply.subico|DcHtml|default='fa-angle-double-right'}"></i>
                            <a class="text-{:DcDefault(input('controll').input('action'),$subnav['controll'].$subnav['action'],'purple','muted')}" href="{$subnav.link|remove_xss}">{$subnav.title|DcHtml|DcSubstr=0,6,false}</a>
                        </li>
                        {/volist}
                    </ul>
                </li>        
            {/volist}
            <!---->
            <li class="nav-item mb-2 pl-md-2">
                <h6>
                    <i class="fa fa-fw fa-rocket"></i>
                    <a class="text-dark" href="#menu-apply" data-toggle="collapse">{:lang('menuApply')}</a>
                </h6>
                <ul class="list-unstyled collapse pl-2 {in name="controll" value="apply"}show{/in}" id="menu-apply" data-parent="#menu-parent">
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-archive"></i>
                        <a class="text-{:DcDefault($controll.$action,'applyindex','purple','muted')}" href="{:DcUrl('admin/apply/index','','')}">{:lang('applyIndex')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-cloud"></i>
                        <a class="text-{:DcDefault($controll.$action,'applystore','purple','muted')}" href="{:DcUrl('admin/apply/store','','')}">{:lang('applyStore')}</a>
                    </li>
                    <li class="list-item mb-2">
                        <i class="fa fa-fw fa-gear"></i>
                        <a class="text-{:DcDefault($controll.$action,'applycreate','purple','muted')}" href="{:DcUrl('admin/apply/create','','')}">{:lang('applyInfo')}</a>
                    </li>
                </ul>
            </li>
            <!--ul end-->
            </ul>
        </div>
    </div>
    <!--main-left end-->
    <div class="main-right col-12 col-md-10 col-xl-11 pt-2">
        {block name="main"}<!-- main -->{/block}
        <footer class="text-center bg-white border-top mt-3 py-3">
            Powered by <a class="text-purple" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a>
			<small>{:config('daicuo.version')}</small>
            {if condition="config('common.apply_name')"}
            & <a class="text-purple" href="{:DcUrl('/admin/apply/jump','module='.config('common.apply_module'),'')}" target="_blank">{:config('common.apply_name')}</a> <small>{:config('common.apply_version')}</small>
            {/if}
        </footer>
    </div>
    <!--main-right end-->
</div>
</div>
{block name="js"}<!-- js -->{/block}
</body>
</html>