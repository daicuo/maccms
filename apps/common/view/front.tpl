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
<script src="{:DcUrlJs($domain, $path_root.'public/js/base.js')}" data-id="daicuo" data-file="{$file}" data-root="{$path_root}" data-view="{$path_view}" data-upload="{$path_upload}" data-module="{$module}" data-controll="{$controll}" data-action="{$action}" data-page="{$page}" data-user-id="{$user.user_id|default=0}" data-lang="{:config('default_lang')}"></script>{block name="header_more"}{/block}
<!-- theme.js -->
<script src="{:DcUrlJs($domain, $path_root.$path_view.'theme.js')}"></script>
{:config('common.header_tongji')}
</head>
<body>
<!-- -->
<div class="modal fade dc-modal">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"></div>
</div>
<!-- -->
<header role="header">
  {block name="header"}<!-- header -->{/block}
</header>
<main role="main">
  {block name="main"}<!-- main -->{/block}
</main>
<footer role="footer">
  {block name="footer"}<!-- footer -->{/block}
</footer>
{block name="js"}<!-- js -->{/block}
<div class="d-none">
  {:config('common.site_tongji')}
</div>
<!-- -->
</body>
</html>