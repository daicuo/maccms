{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("import_manage")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("import_manage")}
</h6>
<form action="{:DcUrlAddon(['module'=>'database','controll'=>'import','action'=>'delete'])}" method="post" data-toggle="form">
<div class="toolbar mb-2" id="toolbar">
  <a class="btn btn-sm btn-light border" href="javascript:;" data-toggle="reload">
    <i class="fa fa-refresh fa-fw"></i> {:lang('refresh')}
  </a>
  <button class="btn btn-sm btn-danger" type="submit" data-toggle="submit">
    <i class="fa fa-trash"></i> {:lang('delete')}
  </button>
</div>
{:DcBuildTable([
    'data-escape'             => 'true',
    'data-toggle'             => 'bootstrap-table',
    'data-url'                => DcUrlAddon(['module'=>'database','controll'=>'import','action'=>'index']),
    'data-buttons-prefix'     => 'btn',
    'data-buttons-class'      => 'purple',
    'data-buttons-align'      => 'none float-md-right',
    'data-icon-size'          => 'sm',
    
    'data-toolbar'            => '.toolbar',
    'data-toolbar-align'      => 'none float-md-left',
    'data-search-align'       => 'none float-md-right',
    'data-search'             => 'false',
    'data-show-search-button' => 'true',
    'data-show-refresh'       => 'true',
    'data-show-toggle'        => 'true',
    'data-show-fullscreen'    => 'true',
    'data-smart-display'      => 'true',
    
    'data-unique-id'          => 'id',
    'data-id-field'           => 'id',
    'data-select-item-name'   => 'id[]',
    'data-query-params-type'  => 'params',
    //'data-query-params'       => 'queryParams',
    'data-sort-name'          => 'ctime',
    'data-sort-order'         => 'desc',
    //'data-sort-class'       => 'table-active',
    //'data-sort-stable'      => 'false',
    
    //'data-side-pagination'  => 'server',
    //'data-total-field'      => 'total',
    //'data-data-field'       => 'data',
    
    //'data-pagination'                  => 'true',
    //'data-pagination-h-align'        => 'left',
    //'data-pagination-detail-h-align' => 'right',
    //'data-pagination-v-align'        => 'top',
    //'data-show-extended-pagination'  => 'true',
    
    //'data-page-number'        => $page,
    //'data-page-size'          => '30',
    //'data-page-list'          => [],

    'columns'=>[
        [
            'data-checkbox'=>'true',
        ],
        [
            'data-field'=>'name',
            'data-title'=>lang('import_name'),
            'data-align'=>'left',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'size',
            'data-title'=>lang('import_size'),
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'extension',
            'data-title'=>lang('import_extension'),
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'ctime',
            'data-title'=>lang('import_ctime'),
            'data-sortable'=>'true',
            'data-sort-name'=>'ctime',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'count',
            'data-title'=>lang('import_count'),
            'data-sortable'=>'true',
            'data-sort-name'=>'count',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'operate',
            'data-title'=>lang('operate'),
            //'data-width'=>'20',
            //'data-width-unit'=>'%',
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
    var $urlUpdate = '?module=database&controll=import&action=update&id='+row.id;
    var $urlDelete = '?module=database&controll=import&action=delete&id='+row.id;
    return '<div class="btn-group btn-group-sm"><a class="btn btn-outline-secondary bg-light" href="'+$urlUpdate+'" data-toggle="get" data-modal-lg="true">{:lang("import_backup")}</a><a class="btn btn-outline-secondary" href="'+$urlDelete+'" data-toggle="delete">{:lang("delete")}</a></div>';
}
</script>
{/block}