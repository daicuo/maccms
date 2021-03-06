{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("nav_index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("nav_index")}
</h6>
<!-- -->
<form action="{:DcUrl('admin/nav/delete','','')}" method="post" data-toggle="form">
<input type="hidden" name="_method" value="delete">
<div id="toolbar" class="toolbar mb-2">
  <a class="btn btn-sm btn-light border" href="javascript:;" data-toggle="reload">
    <i class="fa fa-refresh fa-fw"></i>
    {:lang('refresh')}
  </a>
  <a class="btn btn-sm btn-outline-purple" href="{:DcUrl('admin/nav/create',$query,'')}" data-toggle="create" data-modal-lg="true" data-callback="callAjax">
    <i class="fa fa-plus fa-fw"></i>
    {:lang('create')}
  </a>
  <button class="btn btn-sm btn-outline-danger" type="submit" data-toggle="delete">
    <i class="fa fa-trash"></i>
    {:lang('delete')}
  </button>
</div>
{:DcBuildTable([
    'data-toolbar'=>'.toolbar',
    'data-toolbar-align'=>'none float-md-left',
    'data-search-align'=>'none float-md-right',
    'data-toggle'=>'bootstrap-table',
    'data-url' => DcUrl('admin/nav/index', $query, ''),
    'data-buttons-class'=>'purple',
    'data-icon-size'=>'sm',
    'data-escape'=>'true',
    'data-search'=>'true',
    //'data-show-search-button'=>'true',
    'data-unique-id'=>'op_id',
    'data-id-field'=>'op_id',
    'data-select-item-name'=>'id[]',
    'data-query-params-type'=>'params',
    //'data-query-params'=>'queryParams',
    'data-sort-name'=>'op_order',
    'data-sort-order'=>'asc',
    //'data-sort-class'=>'table-active',
    //'data-sort-stable'=>'false',
    
    //'data-pagination'=> 'true',
    //'data-pagination-h-align'=>'left',
    //'data-pagination-detail-h-align'=>'right',
    //'data-pagination-v-align'=>'top',
    //'data-show-extended-pagination'=> 'true',

    //'data-page-number'=> $page,
    //'data-page-size'=> '10',
    //'data-page-list'=>'[10, 25, 50, 100]',
    
    'data-side-pagination'=> 'server',
    //'data-total-field'=>'total',
    //'data-data-field'=>'rows',
    'columns'=>[
        [
            'data-checkbox'=>'true',
        ],
        [
            'data-field'=>'op_id',
            'data-title'=>'id',
            'data-width'=>'5',
            'data-width-unit'=>'%',
            'data-sortable'=>'true',
            'data-sort-name'=>'op_id',
            'data-sort-order'=>'desc',
            'data-class'=>'',
            'data-align'=>'center',
            'data-valign'=>'middle',
            'data-halign'=>'center',
            'data-falign'=>'center',
            'data-visible'=>'true',
            'data-formatter'=>'',
            'data-footer-formatter'=>'',
        ],
        [
            'data-field'=>'op_order',
            'data-title'=>lang('weight'),
            'data-width'=>'5',
            'data-width-unit'=>'%',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'daicuo.table.sort',
        ],
        [
            'data-field'=>'nav_text',
            'data-width'=>'30',
            'data-width-unit'=>'%',
            'data-title'=>lang('nav_text'),
        ],
        [
            'data-field'=>'nav_ico',
            'data-title'=>lang('ico'),
            'data-width'=>'50',
            'data-width-unit'=>'px',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'callNavIco',
        ],
        [
            'data-field'=>'nav_target',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-title'=>lang('target'),
        ],
        [
            'data-field'=>'nav_active',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-title'=>lang('active'),
        ],
        [
            'data-field'=>'op_status_text',
            'data-title'=>lang('status'),
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'op_module',
            'data-title'=>lang('module'),
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'callModule',
        ],
        [
            'data-field'=>'op_controll',
            'data-title'=>lang('controll'),
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'callControll',
        ],
        [
            'data-field'=>'op_action',
            'data-title'=>lang('action'),
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'callAction',
        ],
        [
            'data-field'=>'operate',
            'data-title'=>lang('operate'),
            'data-width'=>'150',
            'data-width-unit'=>'px',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'callOperate',
        ]
    ]
])}
</form>
{/block}
<!-- -->
{block name="js"}
<script language="javascript">
//回调操作
callAjax = function($data, $status, $xhr) {
    daicuo.bootstrap.dialogForm($data);
    daicuo.admin.navType($('.dc-modal #nav_type').val());
}
callNavIco = function(value, row, index, field){
    return '<i class="fa-lg text-muted '+value+'"></i>';
}
callModule = function(value, row, index, field){
    var $url = daicuo.config.file + '/' + daicuo.config.controll + '/index?op_module='+value;
    return '<a class="text-purple" href="'+$url+'">'+value+'</a>';
}
callControll = function(value, row, index, field){
    var $url = daicuo.config.file + '/' + daicuo.config.controll + '/index?op_controll='+value;
    return '<a class="text-purple" href="'+$url+'">'+value+'</a>';
}
callAction = function(value, row, index, field){
    var $url = daicuo.config.file + '/' + daicuo.config.controll + '/index?op_action='+value;
    return '<a class="text-purple" href="'+$url+'">'+value+'</a>';
}
callOperate = function(value, row, index, field){
    var $url_preview = row.nav_link;
    var $url_edit = daicuo.config.file + '/' + daicuo.config.controll + '/edit?id='+row.op_id;
    var $url_delete = daicuo.config.file + '/' + daicuo.config.controll + '/delete?id='+row.op_id;
    return '<div class="btn-group btn-group-sm"><a class="btn btn-outline-secondary" href="'+$url_preview+'" target="_blank"><i class="fa fa-fw fa-link"></i></a><a class="btn btn-outline-secondary bg-light" href="'+$url_edit+'" data-toggle="edit" data-modal-lg="true" data-callback="callAjax"><i class="fa fa-fw fa-pencil"></i></a><a class="btn btn-outline-secondary" href="'+$url_delete+'" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i></a></div>';
}
//事件定义
$(document).on("change", '#nav_type', function(){
	daicuo.admin.navType($(this).val());
});
</script>
{/block}