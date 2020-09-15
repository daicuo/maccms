<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="format-detection" content="telephone=no"/>{block name="header_meta"}
<title>DaiCuo</title>{/block}
<!-- fonts -->
<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
<!-- jquery -->
<script src="//lib.baomitu.com/jquery/3.3.1/jquery.min.js"></script>
<!-- bootsrtap -->
<link href="//lib.baomitu.com/twitter-bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="//lib.baomitu.com/twitter-bootstrap/4.4.1/js/bootstrap.min.js"></script>{block name="header_more"}{/block}
<!-- base.css -->
<link href="{$path_root}public/static/base.css" rel="stylesheet">
<!-- base.js --> 
<script src="{$path_root}public/static/base.js" data-id="daicuo" data-file="{$file}" data-root="{$path_root}" data-view="{$path_view}" data-module="{$module}" data-controll="{$controll}" data-action="{$action}" data-page="{$page}" data-userId="{$user.user_id|default=0}"></script>
<!--语言包 -->
<script src="{$path_root}public/static/{:config('default_lang')}.js"></script>
<!-- 主题 -->
<link href="{$path_root}{$path_view}theme.css" rel="stylesheet">
<script src="{$path_root}{$path_view}theme.js"></script>
</head>
<body>
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