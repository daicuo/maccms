<form class="{$class|default='form-group'}" action="{$action}" method="{$method|default='post'}" data-toggle="{:DcBool($ajax,'form')}" data-callback="{$callback}" target="{$target|default='_self'}" role="form">
  <fieldset {:DcBool($disabled,'disabled')}>
{volist name="items" id="form"}
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
{case value="select.custom"}
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
{default /}
    {include file="$view.default" /}
{/switch}
{/volist}
  <div class="form-group text-center mb-0">
    <button type="submit" class="btn btn-purple">{$submit}</button>
    {if $reset}
    <button type="reset" class="btn btn-info">{$reset}</button>
    {/if}
    {if $close}
    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">{$close}</button>
    {/if}
  </div>
  </fieldset>
</form>