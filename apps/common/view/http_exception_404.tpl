<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="format-detection" content="telephone=no"/>
<title>{:lang('error404')}</title>
<style>
body{
	font-family: 'Microsoft Yahei', Verdana, arial, sans-serif;
	font-size:14px;
	background:#FCFCFC;
}
a{
	text-decoration:none;
	color:#174B73;
}
a:hover{
	text-decoration:none;
	color:#FF6600;
}
h2{
	border-bottom:1px solid #DDD;
	padding:10px 0;
  font-size:25px;
}
.title{
	margin:4px 0;
	color:#F60;
	font-weight:bold;
}
.notice{
  padding:20px 0;
	color:#666;
	background:#FCFCFC;
}
.center{
	text-align:center;
}
.powered{
	padding-top:10px;
}
.text,a.text:hover{
	color:#FCFCFC;
}
</style>
</head>
<body>
<div class="notice">
  <h2 class="center">
    {:lang('error404')}
  </h2>
  <div class="center">
    [ <a class="title" href="javascript:history.back()">{:lang('goBack')}</a> ]
    [ <a class="title" href="<?php echo(strip_tags($_SERVER['PHP_SELF']))?>">{:lang('goHome')}</a> ]
  </div>
  <p class="center text">
    powered by <a class="text" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a>
  </p>
</div>
<div style="display:none">
    <script type="text/javascript" src="https://js.users.51.la/16951758.js"></script>
</div>
</body>
</html>
