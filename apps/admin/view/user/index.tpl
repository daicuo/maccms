{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("user_index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("user_index")}
</h6>
<!-- -->
<form action="{:DcUrl('admin/user/delete','','')}" method="post" data-toggle="form">
<div class="toolbar mb-2" id="toolbar">
    <a class="btn btn-sm btn-light border" href="javascript:;" data-toggle="reload">
        <i class="fa fa-refresh fa-fw"></i> {:lang('refresh')}
    </a>
    <a class="btn btn-sm btn-outline-purple" href="{:DcUrl('admin/user/create',$query,'')}" data-toggle="create" data-modal-lg="true">
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
    'data-url' => DcUrl('admin/user/index', $query, ''),
    'data-buttons-class'=>'purple',
    'data-icon-size'=>'sm',
    'data-escape'=>'true',
    'data-search'=>'true',
    'data-show-search-button'=>'true',
    'data-unique-id'=>'user_id',
    'data-id-field'=>'user_id',
    'data-select-item-name'=>'id[]',
    'data-query-params-type'=>'params',
    //'data-query-params'=>'callQuery',
    'data-sort-name'=>'user_id',
    'data-sort-order'=>'desc',
    //'data-sort-class'=>'table-active',
    //'data-sort-stable'=>'false',
    'data-pagination'=> 'true',
    'data-page-number'=> $page,
    'data-page-size'=> '20',
    'data-page-list'=>'[20, 50, 100]',
    //'data-show-extended-pagination'=> 'true',
    //'data-pagination-h-align'=>'left',
    //'data-pagination-detail-h-align'=>'right',
    //'data-pagination-v-align'=>'top',
    'data-side-pagination'=> 'server',
    'data-total-field'=>'total',
    'data-data-field'=>'data',
    'columns'=>[
        [
            'data-checkbox'=>'true',
        ],
        [
            'data-field'=>'user_id',
            'data-title'=>'id',
            'data-sortable'=>'true',
            //'data-sort-name'=>'hook_name',
            //'data-sort-order'=>'asc',
            //'data-width'=>'30',
            //'data-width-unit'=>'px',
            'data-class'=>'',
            'data-align'=>'center',
            'data-valign'=>'middle',
            'data-halign'=>'center',
            'data-falign'=>'center',
            'data-visible'=>'true',
            //'data-formatter'=>'',
            //'data-footer-formatter'=>'',
        ],
        [
            'data-field'=>'user_name',
            //'data-width'=>'30',
            //'data-width-unit'=>'%',
            'data-title'=>lang('user_name'),
        ],
        [
            'data-field'=>'user_email',
            'data-title'=>lang('user_email'),
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'user_mobile',
            'data-title'=>lang('user_mobile'),
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'user_status_text',
            'data-title'=>lang('user_status'),
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'user_create_time',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-title'=>lang('user_create_time'),
        ],
        [
            'data-field'=>'user_update_time',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-title'=>lang('user_update_time'),
        ],
        [
            'data-field'=>'user_create_ip',
            'data-title'=>lang('user_create_ip'),
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'user_update_ip',
            'data-title'=>lang('user_update_ip'),
            'data-align'=>'center',
            'data-halign'=>'center',
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
callOperate = function(value, row, index, field){
    var $url_edit = daicuo.config.file + '/' + daicuo.config.controll + '/edit?id='+row.user_id;
    var $url_delete = daicuo.config.file + '/' + daicuo.config.controll + '/delete?id='+row.user_id;
    return '<div class="btn-group btn-group-sm"><a class="btn btn-outline-secondary bg-light" href="'+$url_edit+'" data-toggle="edit" data-modal-lg="true"><i class="fa fa-fw fa-pencil"></i></a><a class="btn btn-outline-secondary" href="'+$url_delete+'" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i></a></div>';
}
</script>
{/block}