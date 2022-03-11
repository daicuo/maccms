<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}">
    <strong>{$form.title}</strong>
  </label>
  <div class="{$form.class_right|default='col-md-6'}">
    <select {if $form['readonly']}readonly{/if} {if $form['disabled']} disabled{/if} {if $form['required']} required{/if} {if $form['autofocus']} autofocus{/if} {if $form['multiple']} multiple{/if} class="{$form.class_right_control|default='form-control form-control-sm'}" id="{:DcEmpty($form['id'],$form['name'])}" name="{$form.name}" data-toggle="select" size="{$form.size|default=1}">
    {foreach name="$form['option']" item="select" key="selectKey"}
    {in name="selectKey" value="$form['value']"}
      <option value="{$selectKey}" selected>{$select}</option>
    {else/}
      <option value="{$selectKey}">{$select}</option>
    {/in}
    {/foreach}
    </select>
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>