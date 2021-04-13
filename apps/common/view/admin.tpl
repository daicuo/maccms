<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="format-detection" content="telephone=no"/>{block name="header_meta"}
<title>DaiCuo</title>{/block}<!-- fonts -->
<link rel="stylesheet" type="text/css" href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.css">
<!-- bootsrtap -->
<link rel="stylesheet" type="text/css" href="//lib.baomitu.com/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
<!-- base.css -->
<link rel="stylesheet" type="text/css" href="{$domain}{$path_root}public/css/base.css?{:config('daicuo.version')}">
<!-- theme.css -->
<link rel="stylesheet" type="text/css" href="{$domain}{$path_root}{$path_view}theme.css?{:config('daicuo.version')}">
<!-- jquery -->
<script type="text/javascript" src="//lib.baomitu.com/jquery/3.3.1/jquery.min.js"></script>
<!-- bootsrtap -->
<script type="text/javascript" src="//lib.baomitu.com/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- headerMore -->{block name="header_more"}{/block}
<!-- base.js-->
<script type="text/javascript" src="{$domain}{$path_root}public/js/base.js?{:config('daicuo.version')}" data-id="daicuo" data-file="{$file}" data-root="{$path_root}" data-view="{$path_view}" data-upload="{$path_upload}" data-module="{$module}" data-controll="{$controll}" data-action="{$action}" data-page="{$page}" data-user-id="{$user.user_id|default=0}" data-lang="{:config('default_lang')}"></script>
<!-- theme.js -->
<script type="text/javascript" src="{$domain}{$path_root}{$path_view}theme.js?{:config('daicuo.version')}"></script>
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
    {volist name=":config('admin_menu.top')" id="menu"}
    <a class="navbar-text text-light ml-md-2" href="{$menu.url|DcHtml}" target="{$menu.target|default='_self'}">
      <i class="{$menu.ico|DcHtml|default='fa fa-fw fa-angle-double-right'}"></i>{$menu.title|DcHtml|DcSubstr=0,6,false}
    </a>
    {/volist}
  </div>
</nav>
<!-- -->
<div class="container-fluid">
  <div class="row">
    <div class="main-left col-4 col-md-2 col-lg-1 col-xl-1 pr-0 d-none d-md-block">
      <div class="sidebar scrollbar pt-2">
        <ul class="nav flex-column" id="menu-parent">
          <!--设置-->
          <li class="nav-item mb-2">
            <h6>
              <i class="fa fa-fw fa-gears"></i>
              <a class="text-dark" href="#menu-option" data-toggle="collapse">{:lang('menu_config')}</a>
            </h6>
            <ul class="list-unstyled collapse {in name="controll" value="op,cache,video,upload"}show{/in}" id="menu-option" data-parent="#menu-parent">
              {volist name=":config('admin_menu.config')" id="menu"}
              <li class="list-item mb-2">
                <i class="text-{:DcDefault($controll.$action,$menu['controll'].$menu['action'],'purple','muted')} {$menu.ico|DcHtml|default='fa fa-fw fa-angle-double-right'}"></i>
                <a class="text-{:DcDefault($controll.$action,$menu['controll'].$menu['action'],'purple','muted')}" href="{$menu.url|DcHtml}" target="{$menu.target|default='_self'}">{$menu.title|DcHtml|DcSubstr=0,6,false}</a>
              </li>
              {/volist}
            </ul>
          </li>
          <!--系统-->
          <li class="nav-item mb-2">
            <h6>
              <i class="fa fa-fw fa-dashboard"></i>
              <a class="text-dark" href="#menu-system" data-toggle="collapse">{:lang('menu_system')}</a>
            </h6>
            <ul class="list-unstyled collapse {in name="controll" value="tool,route,hook,nav,term,category,tag,user,index"}show{/in}" id="menu-system" data-parent="#menu-parent">
              {volist name=":config('admin_menu.system')" id="menu"}
              <li class="list-item mb-2">
                <i class="text-{:DcDefault($controll.$action,$menu['controll'].$menu['action'],'purple','muted')} {$menu.ico|DcHtml|default='fa fa-fw fa-angle-double-right'}"></i>
                <a class="text-{:DcDefault($controll.$action,$menu['controll'].$menu['action'],'purple','muted')}" href="{$menu.url|DcHtml}" target="{$menu.target|default='_self'}">{$menu.title|DcHtml|DcSubstr=0,6,false}</a>
              </li>
              {/volist}
            </ul>
          </li>
          <!--插件-->
          {volist name=":config('admin_menu.addon')" id="menu"}
          <li class="nav-item mb-2">
            <h6>
              <i class="fa fa-fw {$menu.menu_ico|DcHtml|default='fa fa-plus'}"></i>
              <a class="text-dark" href="#menu-{$menu.menu_module}" data-toggle="collapse">{$menu.menu_title|DcHtml|DcSubstr=0,6,false}</a>
            </h6>
            <ul class="list-unstyled collapse {:DcDefault(input('module'),$menu['menu_module'],'show')}" id="menu-{$menu.menu_module}" data-parent="#menu-parent">
              {volist name="menu.menu_items" id="subnav"}
              <li class="list-item mb-2">
                <i class="text-{:DcDefault(input('module').input('controll').input('action'),$menu['menu_module'].$subnav['controll'].$subnav['action'],'purple','muted')} fa fa-fw {$subnav.ico|DcHtml|default='fa fa-angle-double-right'}"></i>
                <a class="text-{:DcDefault(input('module').input('controll').input('action'),$menu['menu_module'].$subnav['controll'].$subnav['action'],'purple','muted')}" href="{$subnav.url|DcHtml}" target="{$subnav.target|default='_self'}">{$subnav.title|DcHtml|DcSubstr=0,6,false}</a>
              </li>
              {/volist}
            </ul>
          </li>
          {/volist}
          <!--应用-->
          <li class="nav-item mb-2">
            <h6>
              <i class="fa fa-fw fa-rocket"></i>
              <a class="text-dark" href="#menu-apply" data-toggle="collapse">{:lang('menu_apply')}</a>
            </h6>
            <ul class="list-unstyled collapse {in name="controll" value="apply,store"}show{/in}" id="menu-apply" data-parent="#menu-parent">
              {volist name=":config('admin_menu.apply')" id="menu"}
              <li class="list-item mb-2">
                <i class="text-{:DcDefault($controll.$action,$menu['controll'].$menu['action'],'purple','muted')} {$menu.ico|DcHtml|default='fa fa-fw fa-angle-double-right'}"></i>
                <a class="text-{:DcDefault($controll.$action,$menu['controll'].$menu['action'],'purple','muted')}" href="{$menu.url|DcHtml}" target="{$menu.target|default='_self'}">{$menu.title|DcHtml|DcSubstr=0,6,false}</a>
              </li>
              {/volist}
            </ul>
          </li>
          <!--ul end-->
        </ul>
      </div>
    </div>
    <!--main-left end-->
    <div class="main-right col-12 col-md-10 col-lg-11 col-xl-11 pt-2 border-left">
      {block name="main"}<!-- main -->{/block}
      <footer class="text-center bg-white py-5">
        Powered by
        {if config('common.apply_name')}
        <a class="text-purple" href="{:DcUrl('/admin/apply/jump','module='.config('common.apply_module'),'')}" target="_blank">{:config('common.apply_name')}</a> <small>{:config('common.apply_version')}</small>
        {else/}
        <a class="text-purple" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a>
        <small>{:config('daicuo.version')}</small>
        {/if}
      </footer>
    </div>
    <!--main-right end-->
  </div>
</div>
{block name="js"}<!-- js -->{/block}
</body>
</html>