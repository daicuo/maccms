<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="renderer" content="webkit">
<title>{:lang('token_get')}</title>
<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/jquery/3.3.1/jquery.slim.min.js"></script>
<link href="//lib.baomitu.com/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
<script src="//lib.baomitu.com/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="//lib.baomitu.com/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
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
  <div class="row pt-5">
    <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 border rounded mt-5">
      <form action="{:DcUrl('api/token/login','','','')}" method="post" role="form">
      <h4 class="text-center my-4">{:lang('token_get')}</h4>
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
        <input type="text" class="form-control" name="user_captcha" value="" required="true" placeholder="{:lang('user_captcha')}" autocomplete="off">
      </div>
      <p class="border rounded py-2 mb-3 text-center">
        <img class="img-fluid" id="captcha" style="cursor:pointer" src="../../public/images/x.gif" alt="{:lang('user_captcha')}" />
      </p>
      <p class="text-center">
        <button class="btn btn-info btn-block mb-3 btn-purple" type="submit">{:lang('login')}</button>
       </p>
      </form>
    </div>
  </div>
</div>
</body>
</html>