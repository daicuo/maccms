<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}">
    <strong>{$form.title}</strong>
  </label>
  <div class="{$form.class_right|default='col-md-6'}">
  {foreach name="$form['option']" item="check" key="checkKey"}
  <div class="{$form.class_right_check|default='form-check py-1'}">
    <input {in name="checkKey" value="$form['value']"}checked{/in} {in name="checkKey" value="$form['readonly']"} readonly{/in} {in name="checkKey" value="$form['disabled']"} disabled{/in} {if $form['required']} required{/if} {if $form['autofocus']} autofocus{/if} type="checkbox" class="{$form.class_right_control|default='form-check-input'}" id="{$form.name}_{$checkKey}" name="{$form.name}" value="{$checkKey}">
    <label class="{$form.class_right_label|default='form-check-label'}" for="{$form.name}_{$checkKey}">{$check}</label>
  </div>
  {/foreach}
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>