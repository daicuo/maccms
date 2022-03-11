<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}">
    <strong>{$form.title}</strong>
  </label>
  <div class="{$form.class_right|default='col-md-6'}">
    <div class="input-group" id="dc-captcha-{$form.id}">
      <input type="text" class="form-control" id="{$form.id}" name="{$form.name}" value="{$form.value|DcHtml}" placeholder="{$form.placeholder|DcHtml}" maxlength="{$form.maxlength}" autocomplete="{$form.autocomplete|DcSwitch}" {if $form['readonly']}readonly{/if} {if $form['disabled']} disabled{/if} {if $form['required']} required{/if} {if $form['autofocus']} autofocus{/if}/>
      <div class="input-group-append">
        <div class="input-group-text p-0">
          <img class="img-fluid dc-captcha" style="max-width:145px;" id="{$form.id}" src="{:DcUrl('captcha/index/index','','')}" alt="{:lang('user_captcha')}" data-toggle="captcha"/>
        </div>
      </div>
    </div>
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>