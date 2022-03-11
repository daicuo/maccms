<div class="modal-content">
  <div class="modal-header">
    <h6 class="modal-title text-purple">{:lang('admin/navs/create')}</h6>
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    {:DcBuildForm([
        'name'     => 'admin/navs/create',
        'class'    => 'bg-white form-create',
        'action'   => DcUrl('admin/navs/save'),
        'method'   => 'post',
        'submit'   => lang('submit'),
        'reset'    => lang('reset'),
        'close'    => false,
        'disabled' => false,
        'ajax'     => true,
        'callback' => '',
        'data'     => $data,
        'items'    => $fields,
    ])}
  </div>
</div>