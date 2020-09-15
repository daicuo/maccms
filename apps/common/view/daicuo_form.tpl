<form class="{$class|default='form-group'}" action="{$action}" method="{$method|default='post'}" role="form" data-toggle="{:DcBool($ajax,'form')}" data-callback="{$callback}">
<fieldset {:DcBool($disabled,'disabled')}>
{volist name="items" id="form"}
{switch name="form.type" }
{case value="hidden"}
    <input name="{$form.name|DcHtml}" id="{:DcEmpty($form['id'],$form['name'])}" type="hidden" value="{$form.value|DcHtml}">
{/case}
{case value="text"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <input
            type="text"
            class="{$form.class_right_control|default='form-control form-control-sm'}" 
            id="{:DcEmpty($form['id'],$form['name'])}"
            name="{$form.name|DcHtml}" 
            value="{$form.value|DcHtml}"
            placeholder="{$form.placeholder|DcHtml}" 
            autocomplete="{:DcEmpty(DcHtml($form['autocomplete']), 'off')}"
            maxlength="{$form.maxlength|DcHtml}"
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            {:DcBool($form['required'],'required')}>
			{if condition="$form['tips']"}
			    <small class="{$form.class_right_tips|default='form-text text-muted mt-2'}">{$form.tips|DcHtml}</small>
            {/if}
        </div>
    </div>
{/case}
<!--textarea-->
{case value="textarea"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <textarea 
            class="{$form.class_right_control|default='form-control form-control-sm'}"
            id="{:DcEmpty($form['id'],$form['name'])}"
            name="{$form.name|DcHtml}"
            rows="{$form.rows|DcHtml|default='5'}"
            placeholder="{$form.placeholder|DcHtml}"
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}>{$form.value|DcHtml}</textarea>
            {if condition="$form['tips']"}
                <small class="{$form.class_right_tips|default='form-text text-muted'}">{$form.tips|DcHtml}</small>
            {/if}
		</div>
    </div>
{/case}
<!--email-->
{case value="email"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <input
            type="email"
            class="{$form.class_right_control|default='form-control form-control-sm'}" 
            id="{:DcEmpty($form['id'],$form['name'])}"
            name="{$form.name|DcHtml}" 
            value="{$form.value|DcHtml}"
            placeholder="{$form.placeholder|DcHtml}" 
            autocomplete="{:DcEmpty(DcHtml($form['autocomplete']), 'off')}"
            maxlength="{$form.maxlength|DcHtml}"
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            {:DcBool($form['required'],'required')}>
			{if condition="$form['tips']"}
			    <small class="{$form.class_right_tips|default='form-text text-muted mt-2'}">{$form.tips|DcHtml}</small>
            {/if}
        </div>
    </div>
{/case}
<!--number-->
{case value="number"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <input
            type="number"
            class="{$form.class_right_control|default='form-control form-control-sm'}" 
            id="{:DcEmpty($form['id'],$form['name'])}"
            name="{$form.name|DcHtml}" 
            value="{$form.value|DcHtml}"
            placeholder="{$form.placeholder|DcHtml}"
            autocomplete="{:DcEmpty(DcHtml($form['autocomplete']), 'off')}"
            maxlength="{$form.maxlength|DcHtml}"
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            {:DcBool($form['required'],'required')}>
			{if condition="$form['tips']"}
			    <small class="{$form.class_right_tips|default='form-text text-muted mt-2'}">{$form.tips|DcHtml}</small>
            {/if}
        </div>
    </div>
{/case}
<!--password-->
{case value="password"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <input
            type="password"
            class="{$form.class_right_control|default='form-control form-control-sm'}" 
            id="{:DcEmpty($form['id'],$form['name'])}"
            name="{$form.name|DcHtml}" 
            value="{$form.value|DcHtml}"
            placeholder="{$form.placeholder|DcHtml}"
            autocomplete="{:DcEmpty(DcHtml($form['autocomplete']), 'off')}"
            maxlength="{$form.maxlength|DcHtml}"
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            {:DcBool($form['required'],'required')}>
			{if condition="$form['tips']"}
			    <small class="{$form.class_right_tips|default='form-text text-muted mt-2'}">{$form.tips|DcHtml}</small>
            {/if}
        </div>
    </div>
{/case}
<!--textarea.json-->
{case value="json"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <textarea 
            class="{$form.class_right_control|default='form-control form-control-sm'}"
            id="{:DcEmpty($form['id'],$form['name'])}"
            name="{$form.name|DcHtml}" 
            rows="{$form.rows|default='5'}"
            placeholder="{$form.placeholder|DcHtml}"
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            data-toggle="json">{$form.value|DcHtml}</textarea>
            {if condition="$form['tips']"}
                <small class="{$form.class_right_tips|default='form-text text-muted'}">{$form.tips|DcHtml}</small>
            {/if}
		</div>
    </div>
{/case}
<!--radio-->
{case value="radio"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
		<div class="{$form.class_right|default='col-md-6'}">
            {foreach name="$form['option']" item="radio" key="radioKey"}
            <div class="{$form.class_right_check|default='form-check py-1'}">
			    <input
                type="radio"
                class="{$form.class_right_control|default='form-check-input'}"
                id="{$form.name}_{$radioKey}"
                name="{$form.name}" 
                value="{$radioKey}" 
                {in name="radioKey" value="$form['readonly']"}readonly{/in}
                {in name="radioKey" value="$form['disabled']"}disabled{/in}
                {:DcDefault($form['value'], $radioKey, 'checked')}>
                <label class="{$form.class_right_tips|default='form-check-label'}" for="{$form.name}_{$radioKey}">{$radio}</label>
            </div>
			{/foreach}
		</div>
	</div>
{/case}
<!--switch-->
{case value="switch"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
		<div class="{$form.class_right|default='col-md-6'}">
		    <div class="custom-control custom-switch">
			    <input 
                type="checkbox" 
                id="{:DcEmpty($form['id'],$form['name'])}"
                class="{$form.class_right_control|default='custom-control-input'}"
                name="{$form.name|DcHtml}" 
                value="on"
                {:DcDefault($form['value'], 'on', 'checked')}
                {:DcBool($form['readonly'],'readonly')}
                {:DcBool($form['disabled'],'disabled')}
                {:DcBool($form['required'],'required')}>
			    <label class="{$form.class_right_tips|default='custom-control-label'}" for="{$form.name}">{$form.tips|DcHtml}</label>
            </div>
		</div>
	</div>
{/case}
<!--select-->
{case value="select"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <select 
            class="{$form.class_right_control|default='form-control'}"
            id="{:DcEmpty($form['id'],$form['name'])}"
            name="{$form.name|DcHtml}" 
            data-toggle="select"
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            {:DcBool($form['required'],'required')}
            {:DcBool($form['multiple'],'multiple')}>
			{foreach name="$form['option']" item="select" key="selectKey"}
                {in name="selectKey" value="$form['value']"}
                    <option value="{$selectKey}" selected>{$select}</option>
                {else/}
                    <option value="{$selectKey}">{$select}</option>
                {/in}
			{/foreach}
			</select>
			{if condition="$form['tips']"}
			    <small class="{$form.class_right_tips|default='form-text text-muted'}">{$form.tips|DcHtml}</small>
			{/if}
        </div>
    </div>
{/case}
<!--select.nav-->
{case value="select.nav"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <select 
            class="{$form.class_right_control|default='form-control'}" 
            id="{:DcEmpty($form['id'],$form['name'])}" 
            name="{$form.name|DcHtml}"
            data-toggle="select" 
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            {:DcBool($form['required'],'required')}
            {:DcBool($form['multiple'],'multiple')}>
			{foreach name="$form['option']" item="select" key="selectKey"}
			    <option value="{$selectKey}"{:DcDefault($form['value'], $selectKey, " selected")}>{$select}</option>
			{/foreach}
			</select>
			{if condition="$form['tips']"}
			    <small class="{$form.class_right_tips|default='form-text text-muted'}">{$form.tips|DcHtml}</small>
			{/if}
        </div>
    </div>
{/case}
<!--select.switch-->
{case value="select.custom"}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            <select 
            class="{$form.class_right_control|default='custom-select custom-select-sm'}"
            id="{:DcEmpty($form['id'],$form['name'])}" 
            name="{$form.name|DcHtml}"
            data-toggle="select" 
            {:DcBool($form['readonly'],'readonly')}
            {:DcBool($form['disabled'],'disabled')}
            {:DcBool($form['required'],'required')}
            {:DcBool($form['multiple'],'multiple')}>
			{foreach name="$form['option']" item="select" key="selectKey"}
			    <option value="{$selectKey}"{:DcDefault($form['value'], $selectKey, " selected")}>{$select}</option>
			{/foreach}
			</select>
			{if condition="$form['tips']"}
			    <small class="{$form.class_right_tips|default='form-text text-muted'}">{$form.tips|DcHtml}</small>
			{/if}
        </div>
    </div>
{/case}
{default /}
    <div class="{$form.class|default='row form-group'}">
        <label class="{$form.class_left|default='col-md-2'}" for="{$form.name|DcHtml}"><strong>{$form.title|DcHtml}</strong></label>
        <div class="{$form.class_right|default='col-md-6'}">
            {:lang('unSupport')}
        </div>
    </div>
{/switch}
{/volist}
    <div class="form-group text-center mb-0">
        <button type="submit" class="btn btn-purple">{$submit}</button>
        {if condition="$reset"}
        <button type="reset" class="btn btn-info">{$reset}</button>
        {/if}
        {if condition="$close"}
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">{$close}</button>
        {/if}
    </div>
</fieldset>
</form>