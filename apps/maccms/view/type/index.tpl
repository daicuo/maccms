<div class="modal-content">
  <div class="modal-body">
    <form class="bg-white px-2 py-2 form-bind" action="../addon/index/?module=maccms&controll=type&action=update" method="post" data-toggle="form" data-callback="callAjax" target="_blank">
      <input name="api" type="hidden" value="{$api}">
      <div class="row form-group">
        {volist name="list" id="maccms"}
        <div class="col-12 col-md-3">
            <div class="form-check">
              <input name="type_name[{$maccms.type_id}]" type="hidden" value="{$maccms.type_name}">
              <input class="form-check-input" name="type_id[{$maccms.type_id}]" type="checkbox" value="{$maccms.type_id}" checked>
              <label class="form-check-label">{$maccms.type_name}</label>
            </div>
        </div>
        {/volist}
      </div>
      <div class="form-group text-center mb-0">
        <button type="submit" class="btn btn-purple">提交</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">关闭</button>
      </div>
    </form>
  </div>
</div>