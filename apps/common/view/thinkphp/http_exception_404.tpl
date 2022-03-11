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
    color:#666;
	background:#FCFCFC;
    overflow-x:hidden;
	text-align:center;
}
a{
	text-decoration:none;
	color:#333;
}
a:hover{
	text-decoration:none;
	color:#000;
}
h2{
	border-bottom:1px solid #DDD;
	padding:15px 0;
    font-size:25px;
}
.title{
	margin:10px 5px;
	color:#F60;
	font-weight:bold;
}
.powered{
    position: absolute;
    width: 100%;
    bottom: 10px;
}
</style>
</head>
<body class="notice">
  <h2>
    {:lang('error404')}
  </h2>
  <p>
    [<a class="title" href="javascript:history.back()">{:lang('goBack')}</a>]
    [<a class="title" href="/">{:lang('goHome')}</a>]
  </p>
  <p class="powered">
    powered by <a href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a>
  </p>
  <div style="display:none">
    <script src="https://cdn.daicuo.cc/51la/16951758.js"></script>
  </div>
</body>
</html>
