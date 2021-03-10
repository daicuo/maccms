<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="renderer" content="webkit">
<title>{:lang('admin_login')}</title>
<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/jquery/3.3.1/jquery.slim.min.js"></script>
<link href="//lib.baomitu.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="//lib.baomitu.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<link href="{$path_root}public/css/base.css" rel="stylesheet">
<link href="{$path_root}{$path_view}theme.css" rel="stylesheet">
<script>
$(document).ready(function(){
    $('#captcha').attr('src', '../../?s=captcha&r=' + Math.random());
    $('#captcha').on('click',function(){
        $(this).attr('src', '../../?s=captcha&r=' + Math.random());
    });
});
</script>
</head>
<body class="bg-light">
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 pt-5">
      <div class="card text-center">
        <div class="card-header h5">
          {:lang('admin_login')}
        </div>
        <div class="card-body pb-2">
          <form action="{:DcUrl('admin/index/login','','','')}" method="post" role="form">
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
            </div>
            <input type="text" class="form-control" name="user_name" value="" required="true" placeholder="{:lang('user_name')}">
          </div>
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" name="user_pass" value="" required="true" placeholder="{:lang('user_pass')}">
          </div>
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-fw fa-key"></i></span>
            </div>
            <input type="text" class="form-control" name="user_captcha" value="" required="true" autocomplete="off" placeholder="{:lang('user_captcha')}">
          </div>
          <p class="card-text border rounded py-2 mb-3">
            <img class="img-fluid" id="captcha" style="cursor:pointer" src="{$path_root}public/images/x.gif" alt="{:lang('user_captcha')}" />
          </p>
          <p class="card-text">
              <button class="btn btn-info btn-block mb-3 btn-purple" type="submit">{:lang('login')}</button>
           </p>
          </form>
        </div>
        <div class="card-footer bg-white text-muted">
          {if config('common.apply_name')}
          <a class="text-purple" href="{$api_url}/home/?module={:config('common.apply_module')}" target="_blank">{:config('common.apply_name')}</a> 
          <small>{:config('common.apply_version')}</small>
          {else/}
          <a class="text-purple" href="{:lang('appUrl')}" target="_blank">{:lang('appName')}</a>
          <small>{:config('daicuo.version')}</small>
          {/if}
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>