<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="format-detection" content="telephone=no"/>{block name="header_meta"}
<title>DaiCuo</title>{/block}<!-- jquery -->
<script src="//lib.baomitu.com/jquery/3.3.1/jquery.min.js"></script>
<!-- fonts / bootsrtap -->
<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
<link href="//lib.baomitu.com/twitter-bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="//lib.baomitu.com/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>{block name="header_more"}{/block}
<!-- base.css / base.js-->
<link href="{$domain}{$path_root}public/css/base.css?{:config('daicuo.version')}" rel="stylesheet">
<script src="{$domain}{$path_root}public/js/base.js?{:config('daicuo.version')}" data-id="daicuo" data-file="{$file}" data-root="{$path_root}" data-view="{$path_view}" data-upload="{$path_upload}" data-module="{$module}" data-controll="{$controll}" data-action="{$action}" data-page="{$page}" data-user-id="{$user.user_id|default=0}" data-lang="{:config('default_lang')}"></script>
<!-- 主题 -->
<link href="{$domain}{$path_root}{$path_view}theme.css?{:config('daicuo.version')}" rel="stylesheet">
<script src="{$domain}{$path_root}{$path_view}theme.js?{:config('daicuo.version')}"></script>
</head>
<body>
<!-- -->
<div class="modal fade dc-modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document"></div>
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