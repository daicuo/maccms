{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("hookIndex")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("hookIndex")}
</h6>
<!-- -->
<form action="{:DcUrl('admin/hook/delete','','')}" method="post" data-toggle="form">
<input type="hidden" name="_method" value="delete">
<div id="toolbar" class="toolbar mb-2">
    <a class="btn btn-sm btn-light border" href="javascript:;" data-toggle="reload">
        <i class="fa fa-refresh fa-fw"></i> {:lang('refresh')}
    </a>
    <a class="btn btn-sm btn-outline-purple" href="{:DcUrl('admin/hook/create',$query,'')}" data-toggle="create" data-modal-lg="true" data-formatter="callAjax">
        <i class="fa fa-plus fa-fw"></i> {:lang('create')}
    </a>
    <button class="btn btn-sm btn-outline-danger" type="submit" data-toggle="delete">
        <i class="fa fa-trash"></i> {:lang('delete')}
    </button>
</div>
{:DcBuildTable([
    'data-toolbar'=>'.toolbar',
    'data-toolbar-align'=>'none float-md-left',
    'data-search-align'=>'none float-md-right',
    'data-toggle'=>'bootstrap-table',
    'data-locale'=>config('default_lang'),
    'data-url' => DcUrl('admin/hook/index', $query, ''),
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
    //'data-pagination'=> 'true',
    //'data-side-pagination'=> 'client',
    //'data-page-number'=> '1',
    //'data-page-size'=> '5',
    //'data-page-list'=>'[10, 25, 50, 100]',
    //'data-show-extended-pagination'=> 'true',
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
            'data-formatter'=>'daicuo.bootstrap.table.sort',
        ],
        [
            'data-field'=>'hook_name',
            'data-width'=>'30',
            'data-width-unit'=>'%',
            'data-title'=>lang('hook_name'),
        ],
        [
            'data-field'=>'hook_path',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-title'=>lang('hook_path'),
        ],
        [
            'data-field'=>'hook_info',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-title'=>lang('hook_info'),
        ],
        [
            'data-field'=>'hook_overlay',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-title'=>lang('hook_overlay'),
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
            'data-field'=>'operate',
            'data-title'=>lang('operate'),
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
<script>
//回调操作
callAjax = function($data, $status, $xhr) {
    //daicuo.admin.nav.typeChange($('.dc-modal #nav_type').val());
}
callModule = function(value, row, index, field){
    var $url = daicuo.config.file + '/' + daicuo.config.controll + '/index?op_module='+value;
    return '<a class="text-purple" href="'+$url+'">'+value+'</a>';
}
callOperate = function(value, row, index, field){
    var $url_preview = row.nav_link;
    var $url_edit = daicuo.config.file + '/' + daicuo.config.controll + '/edit?id='+row.op_id;
    var $url_delete = daicuo.config.file + '/' + daicuo.config.controll + '/delete?id='+row.op_id;
    return '<div class="btn-group btn-group-sm"><a class="btn btn-outline-secondary" href="'+$url_preview+'" target="_blank"><i class="fa fa-fw fa-link"></i></a><a class="btn btn-outline-secondary bg-light" href="'+$url_edit+'" data-toggle="edit" data-modal-lg="true" data-formatter="callAjax"><i class="fa fa-fw fa-pencil"></i></a><a class="btn btn-outline-secondary" href="'+$url_delete+'" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i></a></div>';
}
//事件定义
$(document).on("change", '#nav_type', function(){
	daicuo.admin.nav.typeChange($(this).val());
});
</script>
{/block}