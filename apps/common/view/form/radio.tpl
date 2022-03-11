<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}">
    <strong>{$form.title}</strong>
  </label>
  <div class="{$form.class_right|default='col-md-6'}">
  {foreach name="$form['option']" item="radio" key="radioKey"}
    <div class="{$form.class_right_check|default='form-check py-1'}">
      <input {in name="radioKey" value="$form['value']"}checked{/in} {in name="radioKey" value="$form['readonly']"} readonly{/in} {in name="radioKey" value="$form['disabled']"} disabled{/in} {if $form['required']} required{/if} {if $form['autofocus']} autofocus{/if} type="radio" class="{$form.class_right_control|default='form-check-input'}" id="{$form.name}_{$radioKey}" name="{$form.name}" value="{$radioKey}">
      <label class="{$form.class_right_label|default='form-check-label'}" for="{$form.name}_{$radioKey}">{$radio}</label>
    </div>
  {/foreach}
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>