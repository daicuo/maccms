{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("database_manage")}－{:lang('appName')}</title>
{/block}
{block name="header_addon"}
<link href="{$path_root}{$path_addon}view/theme.css">
<script src="{$path_root}{$path_addon}view/theme.js"></script>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("database_manage")}
</h6>
<form action="{:DcUrlAddon(['module'=>'database','controll'=>'admin','action'=>'export'])}" method="post" data-toggle="form">
<div class="toolbar mb-2" id="toolbar">
  <div class="btn-group btn-group-sm">
    <button class="btn btn-outline-secondary" type="submit" data-toggle="submit" data-action="{:DcUrlAddon(['module'=>'database','controll'=>'admin','action'=>'export'])}">
      <i class="fa fa-trash"></i> {:lang('database_manage_export')}
    </button>
    <button class="btn btn-outline-secondary" type="submit" data-toggle="submit" data-action="{:DcUrlAddon(['module'=>'database','controll'=>'admin','action'=>'optimize'])}">
      <i class="fa fa-trash"></i> {:lang('database_manage_optimize')}
    </button>
    <button class="btn btn-outline-secondary" type="submit" data-toggle="submit" data-action="{:DcUrlAddon(['module'=>'database','controll'=>'admin','action'=>'repair'])}">
      <i class="fa fa-trash"></i> {:lang('database_manage_repair')}
    </button>
  </div>
</div>
{:DcBuildTable([
    'data-escape'             => 'true',
    'data-toggle'             => 'bootstrap-table',
    'data-url'                => DcUrlAddon(['module'=>'database','controll'=>'admin','action'=>'index']),
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
    'data-sort-name'          => 'id',
    'data-sort-order'         => 'desc',
    'data-sort-class'         => 'table-active',
    'data-sort-stable'        => 'false',
    
    //'data-side-pagination'  => 'server',
    //'data-total-field'      => 'total',
    //'data-data-field'       => 'data',
    
    //'data-pagination'                => 'true',
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
            'data-field'=>'Name',
            'data-title'=>lang('database_name'),
            'data-align'=>'left',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'Rows',
            'data-title'=>lang('database_rows'),
            'data-sortable'=>'true',
            'data-sort-name'=>'Rows',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'Data_length',
            'data-title'=>lang('database_length'),
            'data-sortable'=>'true',
            'data-sort-name'=>'Data_length',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'Data_free',
            'data-title'=>lang('database_free'),
            'data-sortable'=>'true',
            'data-sort-name'=>'Data_free',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'Index_length',
            'data-title'=>lang('database_length_index'),
            'data-sortable'=>'true',
            'data-sort-name'=>'Index_length',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'Create_time',
            'data-title'=>lang('database_create_time'),
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'',
        ],
        [
            'data-field'=>'Update_time',
            'data-title'=>lang('database_update_time'),
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'',
        ],
        [
            'data-field'=>'Check_time',
            'data-title'=>lang('database_check_time'),
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'',
        ],
        [
            'data-field'=>'Comment',
            'data-title'=>lang('database_comment'),
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
    var $urlOptimize = '?module=database&controll=admin&action=optimize&id='+row.id;
    var $urlRepair = '?module=database&controll=admin&action=repair&id='+row.id;
    return '<div class="btn-group btn-group-sm"><a class="btn btn-outline-secondary bg-light" href="'+$urlOptimize+'" data-toggle="get" data-modal-lg="true">{:lang("database_optimize")}</a><a class="btn btn-outline-secondary" href="'+$urlRepair+'" data-toggle="get">{:lang("database_repair")}</a></div>';
}
</script>
{/block}