<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}">
    <strong>{$form.title}</strong>
  </label>
  <div class="{$form.class_right|default='col-md-6'}">
    <div class="input-group dc-datetime" id="dc-datetime-{$form.id}" data-target-input="nearest" data-toggle="datetime">
      <input type="text" class="form-control datetimepicker-input" id="{$form.id}" name="{$form.name}" value="{$form.value|DcHtml}" placeholder="{$form.placeholder|DcHtml}" maxlength="{$form.maxlength}" autocomplete="{$form.autocomplete|DcSwitch}" data-target="#dc-datetime-{$form.id}" {if $form['readonly']}readonly{/if} {if $form['disabled']} disabled{/if} {if $form['required']} required{/if} {if $form['autofocus']} autofocus{/if}/>
      <div class="input-group-append" data-target="#dc-datetime-{$form.id}" data-toggle="datetimepicker">
        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
      </div>
    </div>
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>