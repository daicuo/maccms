<form class="{$class|default='form-group'}" action="{$action}" method="{$method|default='post'}" data-toggle="{:DcBool($ajax,'form')}" data-callback="{$callback}" target="{$target|default='_self'}">
<fieldset {:DcBool($disabled,'disabled')}>
<ul class="nav nav-tabs {$class_tabs|default='mb-2'}" id="formTab">
  {volist name="group" id="groups"}
  <li class="nav-item">
    <a class="nav-link {$class_link|default='rounded-0'} {:DcDefault($i, 1, 'active')}" id="tab-{$i}" data-toggle="tab" href="#group-{$i}">{$groups.title}</a>
  </li>
  {/volist}
</ul>
<div class="tab-content {$class_content|default='border p-3'}" id="formTabContent">
  {volist name="group" id="groups" key="n"}
  <div class="tab-pane fade {:DcDefault($n, 1, 'show active')}" id="group-{$n}"> 
    {volist name="groups.items" id="form"}
    {switch name="form.type"}
    {case value="html"}
        {$form.value}
    {/case}
    {case value="hidden"}
        {include file="$view.hidden" /}
    {/case}
    {case value="text"}
        {include file="$view.text" /}
    {/case}
    {case value="textarea"}
        {include file="$view.textarea" /}
    {/case}
    {case value="email"}
        {include file="$view.email" /}
    {/case}
    {case value="url"}
        {include file="$view.url" /}
    {/case}
    {case value="number"}
        {include file="$view.number" /}
    {/case}
    {case value="password"}
        {include file="$view.password" /} 
    {/case}
    {case value="json"}
        {include file="$view.json" /}
    {/case}
    {case value="radio"}
        {include file="$view.radio" /}
    {/case}
    {case value="checkbox"}
        {include file="$view.checkbox" /}
    {/case}
    {case value="switch"}
        {include file="$view.switch" /}
    {/case}
    {case value="select"}
        {include file="$view.select" /}
    {/case}
    {case value="custom"}
        {include file="$view.custom" /}
    {/case}
    {case value="image"}
        {include file="$view.image" /}
    {/case}
    {case value="file"}
        {include file="$view.file" /}
    {/case}
    {case value="editor"}
        {include file="$view.editor" /}
    {/case}
    {case value="datetime"}
        {include file="$view.datetime" /}
    {/case}
    {case value="captcha"}
        {include file="$view.captcha" /}
    {/case}
    {default /}
        {include file="$view.default" /}
    {/switch}
    {/volist}
  </div>
  {/volist}
  <div class="{$class_button|default='form-group text-center mb-0'}">
    <button type="submit" class="btn btn-purple">{$submit}</button>
    {if $reset}
    <button type="reset" class="btn btn-info">{$reset}</button>
    {/if}
    {if $close}
    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">{$close}</button>
    {/if}
  </div>
</div>
</fieldset>
</form>