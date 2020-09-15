{extend name="./public/static/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("routeIndex")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<form action="{:DcUrl('admin/route/delete','','')}" method="post" data-toggle="form">
<input type="hidden" name="_method" value="delete">
<h6 class="border-bottom pb-2 text-purple">
	{:lang("routeIndex")}
</h6>
<div id="toolbar" class="toolbar mb-2">
    <a class="btn btn-sm btn-light border" href="javascript:;" data-toggle="reload">
        <i class="fa fa-refresh"></i>
        {:lang('refresh')}
    </a>
    <a class="btn btn-sm btn-light border" href="{:DcUrl('admin/route/create',['op_module'=>$op_module],'')}" data-toggle="create">
        <i class="fa fa-plus"></i>
        {:lang('create')}
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
    'data-url' => DcUrl('admin/route/index', $query, ''),
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
    'data-sort-name'=>'op_id',
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
            'data-sort-order'=>'asc',
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
          'data-field'=>'rule',
          'data-title'=>lang('route_rule'),
        ],
        [
          'data-field'=>'address',
          'data-title'=>lang('route_address'),
        ],
        [
          'data-field'=>'method',
          'data-title'=>lang('route_method'),
          'data-align'=>'center',
          'data-halign'=>'center',
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
callAjax = function($data, $status, $xhr) {
    //$('.form-edit input[name="rule"]').attr('readonly',true);
    daicuo.json.beauty();
}
callModule = function(value, row, index, field){
    var $url = daicuo.config.file + '/' + daicuo.config.controll + '/index?op_module='+value;
    return '<a class="text-purple" href="'+$url+'">'+value+'</a>';
}
callOperate = function(value, row, index, field){
    var $url_preview = daicuo.config.root +row.rule;
    var $url_edit = daicuo.config.file + '/' + daicuo.config.controll + '/edit?id='+row.op_id;
    var $url_delete = daicuo.config.file + '/' + daicuo.config.controll + '/delete?id='+row.op_id;
    return '<div class="btn-group btn-group-sm"><a class="btn btn-outline-secondary" href="'+$url_preview+'" target="_blank"><i class="fa fa-fw fa-link"></i></a><a class="btn btn-outline-secondary bg-light" href="'+$url_edit+'" data-toggle="edit" data-formatter="callAjax"><i class="fa fa-fw fa-pencil"></i></a><a class="btn btn-outline-secondary" href="'+$url_delete+'" data-toggle="delete"><i class="fa fa-fw fa-trash-o"></i></a></div>';
}
</script>
{/block}