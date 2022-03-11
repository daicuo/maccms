<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="renderer" content="webkit">
<title>{:lang('admin_login')}</title>
<!-- fonts -->
<link rel="stylesheet" href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- bootsrtap -->
<link rel="stylesheet" href="//lib.baomitu.com/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
<!-- base.css -->
<link rel="stylesheet" href="{$path_root}public/css/base.css?{:config('daicuo.version')}">
<!-- theme.css -->
<link rel="stylesheet" href="{$path_root}{$path_view}theme.css?{:config('daicuo.version')}">
<!-- jquery -->
<script src="//lib.baomitu.com/jquery/3.3.1/jquery.min.js"></script>
<!-- bootsrtap -->
<script src="//lib.baomitu.com/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- base.js-->
<script src="{$path_root}public/js/base.js?{:config('daicuo.version')}" data-id="daicuo" data-file="{$file}" data-root="{$path_root}" data-view="{$path_view}" data-upload="{$path_upload}" data-module="{$module}" data-controll="{$controll}" data-action="{$action}" data-page="{$page}" data-user-id="{$user.user_id|default=0}" data-lang="{:config('default_lang')}"></script>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 pt-5">
      <div class="card text-center">
        <div class="card-header h6">
          {:lang('admin_login')}
        </div>
        <div class="card-body pb-2">
          <form action="{:DcUrl('admin/index/login')}" method="post" role="form" data-toggle="form">
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-fw fa-user"></i></span>
            </div>
            <input type="text" class="form-control" id="user_name" name="user_name" required="true" placeholder="{:lang('user_name')}" autocomplete="off">
          </div>
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-fw fa-lock"></i></span>
            </div>
            <input type="password" class="form-control" id="user_pass" name="user_pass" required="true" placeholder="{:lang('user_pass')}" autocomplete="off">
          </div>
          <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fa fa-fw fa-key"></i></span>
            </div>
            <input type="text" class="form-control" id="user_captcha" name="user_captcha" required="true" placeholder="{:lang('user_captcha')}" autocomplete="off">
          </div>
          <p class="card-text mb-3">
            <img class="img-fluid w-100 rounded" id="captcha" src="{$path_root}index.php?s=captcha" alt="{:lang('user_captcha')}" data-toggle="captcha"/>
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
<script>
$(document).ready(function(){
    
    window.daicuo.captcha.init();
    
    $(document).on('submit', '[data-toggle="form"]', function() {
        var self = $(this);
        
        self.find('.is-invalid').removeClass('is-invalid');
        
        self.find('.invalid-feedback').remove();
        
        daicuo.ajax.post($(this).attr('action'), $(this).serialize(), function($data, $status, $xhr) {
            var $field = '';
            
            var $msg = $data.msg.split('%');
            
            if($msg.length > 1){
                $field = $msg[0];
                $msg = $msg[1];
            }
            if ($data.code == 1) {
                window.location.href = $data.url;
            }else{
                self.find('#'+$field).removeClass('is-valid').addClass('is-invalid');
                
                self.find('#'+$field).after('<div class="invalid-feedback pt-2 mb-0 text-left">'+$msg+'</div>');
                
                $('#user_captcha').attr('placeholder','{:lang("captcha_rewrite")}').val('');
                
                window.daicuo.captcha.refresh({element:'#captcha'});
            }
        });
        return false;
    });
});
</script>
</body>
</html>