{extend name="apps/common/view/front.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang('token_get')}</title>
{/block}
{block name="main"}
<div class="container">
  <div class="row">
    <div class="col-md-6 offset-md-3 col-lg-4 offset-lg-4 pt-5">
      <div class="card">
        <h4 class="card-header text-center">{:lang('token_get')}</h4>
        <form class="card-body" action="{:DcUrl('api/token/login','','','')}" method="post">
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
        {if DcBool(config('common.site_captcha'))}
        <div class="input-group input-group-sm mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text"><i class="fa fa-fw fa-key"></i></span>
          </div>
          <input type="text" class="form-control" name="user_captcha" value="" required="true" placeholder="{:lang('user_captcha')}" autocomplete="off">
        </div>
        <p class="border rounded py-2 mb-3 text-center">
          <img class="img-fluid" id="captcha" src="../../public/images/x.gif" alt="{:lang('user_captcha')}" data-toggle="captcha"/>
        </p>
        {/if}
        <p class="text-center">
          <button class="btn btn-info btn-block mb-3 btn-purple" type="submit">{:lang('login')}</button>
         </p>
        </form>
      </div>
    </div>
  </div>
</div>
{/block}