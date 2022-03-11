<div class="modal-content">
  <div class="modal-header">
    <h6 class="modal-title text-purple">{:lang('admin/lang/create')}</h6>
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    {:DcBuildForm([
        'name'     => 'admin/lang/create',
        'class'    => 'bg-white px-1 form-create',
        'action'   => DcUrl('admin/lang/save'),
        'method'   => 'post',
        'submit'   => lang('submit'),
        'reset'    => lang('reset'),
        'close'    => lang('close'),
        'disabled' => false,
        'ajax'     => true,
        'callback' => '',
        'data'     => '',
        'items'    => $fields,
    ])}
  </div>
</div>