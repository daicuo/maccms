{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("categoryIndex")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
	{:lang("categoryIndex")}
</h6>
<!-- -->
<form action="{:DcUrl('admin/category/delete','','')}" method="post" data-toggle="form">
<div class="toolbar mb-2" id="toolbar">
    <a class="btn btn-sm btn-light border" href="javascript:;" data-toggle="reload">
        <i class="fa fa-refresh fa-fw"></i> {:lang('refresh')}
    </a>
    <a class="btn btn-sm btn-outline-purple" href="{:DcUrl('admin/category/create',$query,'')}" data-toggle="create" data-modal-lg="true">
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
    'data-url' => DcUrl('admin/category/index', $query, ''),
    'data-buttons-class'=>'purple',
    'data-icon-size'=>'sm',
    'data-escape'=>'true',
    'data-search'=>'true',
    'data-show-search-button'=>'true',
    'data-unique-id'=>'term_id',
    'data-id-field'=>'term_id',
    'data-select-item-name'=>'id[]',
    'data-query-params-type'=>'params',
    //'data-query-params'=>'callQuery',
    'data-sort-name'=>'tree',
    'data-sort-order'=>'desc',
    //'data-sort-class'=>'table-active',
    //'data-sort-stable'=>'false',
    
    'data-pagination'=> 'true',
    //'data-pagination-h-align'=>'left',
    //'data-pagination-detail-h-align'=>'right',
    //'data-pagination-v-align'=>'top',
    //'data-show-extended-pagination'=> 'true',
    
    'data-page-number'=> $page,
    'data-page-size'=> '20',
    'data-page-list'=>[],
    
    'data-side-pagination'=> 'server',
    'data-total-field'=>'total',
    'data-data-field'=>'data',
    'columns'=>[
        [
            'data-checkbox'=>'true',
        ],
        [
            'data-field'=>'term_id',
            'data-title'=>'id',
            'data-width'=>'5',
            'data-width-unit'=>'%',
            'data-sortable'=>'true',
            'data-sort-name'=>'term_id',
            'data-sort-order'=>'asc',
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
            'data-field'=>'term_name',
            'data-title'=>lang('name'),
            'data-align'=>'left',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'term_slug',
            'data-title'=>lang('slug'),
            'data-width'=>'20',
            'data-width-unit'=>'%',
            'data-align'=>'left',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'term_much_parent',
            'data-title'=>lang('parent'),
            'data-width'=>'5',
            'data-width-unit'=>'%',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'term_order',
            'data-title'=>lang('weight'),
            'data-width'=>'6',
            'data-width-unit'=>'%',
            'data-sortable'=>'true',
            'data-sort-name'=>'term_order',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'term_much_count',
            'data-title'=>lang('count'),
            'data-width'=>'6',
            'data-width-unit'=>'%',
            'data-sortable'=>'true',
            'data-sort-name'=>'term_much_count',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'term_status_text',
            'data-title'=>lang('status'),
            'data-width'=>'6',
            'data-width-unit'=>'%',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'term_module',
            'data-title'=>lang('module'),
            'data-width'=>'6',
            'data-width-unit'=>'%',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'callModule',
        ],
        [
            'data-field'=>'operate',
            'data-title'=>lang('operate'),
            'data-width'=>'120',
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
<script>
callModule = function(value, row, index, field){
    var $url = daicuo.config.file + '/' + daicuo.config.controll + '/index?op_module='+value;
    return '<a class="text-purple" href="'+$url+'">'+value+'</a>';
}
callOperate = function(value, row, index, field){
    var $url_edit = daicuo.config.file + '/' + daicuo.config.controll + '/edit?id='+row.term_id;
    var $url_delete = daicuo.config.file + '/' + daicuo.config.controll + '/delete?id='+row.term_id;
    return '<div class="btn-group btn-group-sm"><a class="btn btn-outline-secondary bg-light" href="'+$url_edit+'" data-toggle="edit" data-modal-lg="true"><i class="fa fa-fw fa-pencil"></i></a><a class="btn btn-outline-secondary" href="'+$url_delete+'" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i></a></div>';
}
</script>
{/block}