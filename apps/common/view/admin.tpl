<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="format-detection" content="telephone=no"/>{block name="header_meta"}
<title>DaiCuo</title>{/block}<!-- fonts -->
<link href="https://lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<!-- bootsrtap -->
<link href="https://lib.baomitu.com/twitter-bootstrap/4.6.1/css/bootstrap.min.css" rel="stylesheet">
<!-- base.css -->
<link href="{:DcUrlCss($domain, $path_root.'public/css/base.css')}" rel="stylesheet">
<!-- theme.css -->
<link href="{:DcUrlCss($domain, $path_root.$path_view.'theme.css')}" rel="stylesheet">
<!-- jquery -->
<script src="https://lib.baomitu.com/jquery/3.3.1/jquery.min.js"></script>
<!-- bootsrtap -->
<script src="https://lib.baomitu.com/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<!-- base.js-->
<script src="{:DcUrlJs($domain, $path_root.'public/js/base.js')}" data-id="daicuo" data-file="{$file}" data-root="{$path_root}" data-view="{$path_view}" data-upload="{$path_upload}" data-module="{$module}" data-controll="{$controll}" data-action="{$action}" data-page="{$page}" data-user-id="{$user.user_id|default=0}" data-lang="{:config('default_lang')}"></script>
<!-- more -->{block name="header_more"}{/block}
<!-- theme.js -->
<script src="{:DcUrlJs($domain, $path_root.$path_view.'theme.js')}"></script>
<!-- addon -->{block name="header_addon"}{/block}
</head>
<body>
<!-- -->
<div class="modal fade dc-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"></div>
</div>
<!-- -->
<nav class="navbar navbar-expand-md navbar-dark bg-purple fixed-top">
  <a class="navbar-brand d-none d-md-inline" href="{:DcUrl('admin/index/index')}">
    {:DcEmpty(config('common.apply_name'),lang('appName'))}
  </a>
  <div class="w-100 d-flex justify-content-between justify-content-md-end">
    <button class="navbar-toggler" type="button" data-toggle="main-left">
      <i class="fa fa-navicon text-light"></i>
    </button>
    {volist name=":model('common/Menu','loglic')->select(['status'=>'normal','action'=>'top','limit'=>10,'sort'=>'term_order desc,term_id','order'=>'asc'])" id="menu"}
    <a class="navbar-text text-light ml-md-2" href="{:adminMenuUrl($menu['term_slug'])}" target="{$menu.term_type|default='_self'}">
      <i class="fa fa-fw {$menu.term_info|default='fa-angle-double-right'}"></i>{$menu.term_name|adminMenuName}
    </a>
    {/volist}
  </div>
</nav>
<!-- -->
{:DcHookListen('admin_header', $controll)}
<!-- -->
<div class="container-fluid">
  <div class="row">
    <div class="main-left col-4 col-md-2 col-lg-1 col-xl-1 pr-0 d-none d-md-block">
      <div class="sidebar scrollbar pt-2">
        <ul class="nav flex-column" id="parents">
          {volist name=":model('common/Menu','loglic')->select(['status'=>'normal','result'=>'tree','action'=>'left','sort'=>'term_order desc,term_id','order'=>'asc'])" id="menus" offset="0" length="999"}
          <li class="nav-item mb-2">
            <h6>
              <i class="fa fa-fw {$menus.term_info|default='fa-gears'}"></i>
              <a class="text-dark" href="#son-{$menus.term_slug}" data-toggle="collapse">{$menus.term_name|adminMenuName}</a>
            </h6>
            <ul class="list-unstyled collapse {:adminMenuShow($active,$menus['term_slug'],$menus['_child'])}" id="son-{$menus.term_slug}" data-parent="#parents">
            {volist name="$menus['_child']" id="menu"}
            <li class="list-item mb-2">
              <i class="fa fa-fw {$menu.term_info|default='fa-gear'} {:adminMenuColor($active, $menu['term_slug'])}"></i>
              <a class="{:adminMenuColor($active, $menu['term_slug'])}" href="{:adminMenuUrl($menu['term_slug'])}" target="{$menu.term_type|default='_self'}">{$menu.term_name|adminMenuName}</a>
            </li>
            {/volist}
            </ul>
          </li>
          {/volist}
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
        <a class="text-purple" href="{:DcUrl('/admin/apply/jump','module='.config('common.apply_module'))}" target="_blank">{:config('common.apply_name')}</a> <small>{:config('common.apply_version')}</small>
        {else/}
        <a class="text-purple" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a>
        <small>{:config('daicuo.version')}</small>
        {/if}
      </footer>
    </div>
    <!--main-right end-->
  </div>
</div>
{:DcHookListen('admin_footer', $controll)}
{block name="js"}<!-- js -->{/block}
</body>
</html>