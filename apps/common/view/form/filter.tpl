{volist name="items" id="form"}
{switch name="form.type"}
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
{case value="datetime"}
  {include file="$view.datetime" /}
{/case}
{default /}
   {include file="$view.text" /}
{/switch}
{/volist}