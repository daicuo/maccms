<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}"><strong>{$form.title}</strong></label>
  <div class="{$form.class_right|default='col-md-6'}">
    <div class="custom-control custom-switch">
      <input {if DcSwitch($form['value']) eq 'on'}checked{/if} {if $form['readonly']} readonly{/if} {if $form['disabled']} disabled{/if} {if $form['required']} required{/if} {if $form['autofocus']}autofocus{/if} type="checkbox" class="{$form.class_right_control|default='custom-control-input'}" id="{:DcEmpty($form['id'],$form['name'])}" name="{$form.name}" value="on">
      <label class="{$form.class_right_label|default='custom-control-label'}" for="{$form.name}">{$form.tips}</label>
    </div>
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>