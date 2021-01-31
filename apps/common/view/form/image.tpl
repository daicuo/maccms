<div class="{$form.class|default='row form-group'}">
  <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}">
    <strong>{$form.title}</strong>
  </label>
  <div class="{$form.class_right|default='col-md-6'}">
    <div class="input-group">
      <input {if $form['readonly']}readonly{/if} {if $form['disabled']} disabled{/if} {if $form['required']} required{/if} {if $form['autofocus']} autofocus{/if} type="text" class="{$form.class_right_control|default='form-control form-control-sm'}" id="{:DcEmpty($form['id'],$form['name'])}" name="{$form.name}" value="{$form.value|DcHtml}" placeholder="{$form.placeholder}" maxlength="{$form.maxlength}">
      <div class="input-group-append">
        <button class="btn btn-sm btn-purple dc-image-preview {if $form['value']}d-inline-block{else/}d-none{/if}" type="button" data-toggle="imagePreview" data-target="#{:DcEmpty($form['id'],$form['name'])}" data-cdn="{:config('common.upload_cdn')}" data-root="{:DcRoot()}" data-path="{:config('common.upload_path')}">{:lang('preview')}</button>
        <button class="btn btn-sm btn-purple dc-upload" type="button" data-toggle="upload" data-input="#{:DcEmpty($form['id'],$form['name'])}" data-mime-types="image/*" data-max-size="{$Think.config.common.upload_max_size|default='5mb'}" data-url="{$path_upload}" data-multiple="{$form.multiple|default='false'}" data-on-success="{$form.onSuccess|default='daicuo.form.upSuccess'}" data-on-error="{$form.onError|default='daicuo.form.upError'}" data-on-complete="{$form.onComplete|default='daicuo.form.upComplete'}">{:lang('upload')}</button>
      </div>
    </div>
  </div>
  {if $form['tips']}
  <div class="{$form.class_tips|default='col-md-2 form-text text-muted small'}">
    {$form.tips}
  </div>
  {/if}
</div>