<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}">
    <strong>{$form.title|DcHtml}</strong>
  </label>
  <div class="{$form.class_right|default='col-md-6'}">
    <input {if $form['readonly']}readonly{/if} {if $form['disabled']} disabled{/if} {if $form['required']} required{/if} {if $form['autofocus']} autofocus{/if} type="url" class="{$form.class_right_control|default='form-control form-control-sm'}" id="{:DcEmpty($form['id'],$form['name'])}" name="{$form.name|DcHtml}" value="{$form.value|DcHtml}" placeholder="{$form.placeholder|DcHtml}" maxlength="{$form.maxlength|DcHtml}" autocomplete="{$form.autocomplete|DcSwitch}">
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>