<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="renderer" content="webkit">
<title>{:lang('indexLogin')}</title>
<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/jquery/3.3.1/jquery.slim.min.js"></script>
<link href="//lib.baomitu.com/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="//lib.baomitu.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link href="{$path_root}public/static/base.css" rel="stylesheet">
<link href="{$path_root}{$path_view}theme.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 pt-5">
      <div class="card text-center">
        <div class="card-header h5">
          {:lang('indexLogin')}
        </div>
        <div class="card-body pb-2">
          <form action="{:DcUrl('admin/index/login','','','')}" method="post" role="form">
          <p class="card-text">
              <input type="text" class="form-control form-control-sm" name="user_name" placeholder="{:lang('user_name')}" value="" required="true">
          </p>
          <p class="card-text">
              <input type="password" class="form-control form-control-sm" name="user_pass" placeholder="{:lang('user_pass')}" value="" required="true">
          </p>
          <p class="card-text mt-3">
              <button class="btn btn-info btn-block btn-sm mb-3 btn-purple" type="submit">{:lang('login')}</button>
           </p>
          </form>
        </div>
        <div class="card-footer bg-white text-muted">
          <a class="text-purple" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a>
          {:config('daicuo.version')}
          {if condition="config('common.apply_name')"}
          & <a class="text-purple" href="{:lang('appServer')}/home/?module={:config('common.apply_module')}" target="_blank">{:config('common.apply_name')}</a> <small>{:config('common.apply_version')}</small>
          {/if}
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>