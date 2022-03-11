{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/route/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom text-purple pb-2 mb-0">
  {:lang("admin/route/index")}
</h6>
<div class="form-row collapse" id="filter-row">
  {:DcFormFilter($fields)}
</div>
<form action="{:DcUrl('admin/route/index')}" method="post" data-toggle="form">
<input type="hidden" name="_method" value="delete">
<div class="toolbar" id="toolbar">
  <div class="btn-group btn-group-sm">
    <button type="button" class="btn btn-purple dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      {:lang('action')}
    </button>
    <div class="dropdown-menu">
      <button class="dropdown-item px-3" type="submit" data-action="{:DcUrl('admin/op/status',['value'=>'normal'])}" data-toggle="submit">
        <i class="fa fa-fw fa-eye"></i> {:lang('normal')}
      </button>
      <button class="dropdown-item px-3" type="submit" data-action="{:DcUrl('admin/op/status',['value'=>'hidden'])}" data-toggle="submit">
        <i class="fa fa-fw fa-eye-slash"></i> {:lang('hidden')}
      </button>
      <button class="dropdown-item px-3" type="submit"  data-action="{:DcUrl('admin/route/delete')}" data-toggle="submit">
        <i class="fa fa-fw fa-trash"></i> {:lang('delete')}
      </button>
    </div>
  </div>
  <a class="btn btn-sm btn-danger" href="{:DcUrl('admin/route/create', $query)}" data-toggle="create" data-modal-lg="true">
    <i class="fa fa-fw fa-plus"></i> {:lang('create')}
  </a>
  <a class="btn btn-sm btn-dark" href="#filter-row" data-toggle="collapse">
    <i class="fa fa-fw fa-filter"></i> {:lang('filter')}
  </a>
  <a class="btn btn-sm btn-info" href="javascript:;" data-toggle="refresh">
    <i class="fa fa-fw fa-refresh"></i> {:lang('refresh')}
  </a>
</div>
{:DcBuildTable([
    'data-name'               => 'admin/route/index',
    'data-escape'             => false,
    'data-toggle'             => 'bootstrap-table',
    'data-url'                => DcUrl('admin/route/index', $query),
    'data-url-sort'           => DcUrl('admin/route/sort', ['id'=>'']),
    'data-url-preview'        => DcUrl('admin/route/preview', ['id'=>'']),
    'data-url-edit'           => DcUrl('admin/route/edit', ['id'=>'']),
    'data-url-delete'         => DcUrl('admin/route/delete', ['id'=>'']),
    'data-buttons-prefix'     => 'btn',
    'data-buttons-class'      => 'purple',
    'data-icon-size'          => 'sm',
    
    'data-toolbar'            => '.toolbar',
    'data-toolbar-align'      => 'none float-md-left',
    'data-buttons-align'      => 'right',
    'data-search-align'       => 'none float-md-right',
    'data-search'             => true,
    'data-show-search-button' => true,
    'data-show-refresh'       => false,
    'data-show-toggle'        => true,
    'data-show-fullscreen'    => true,
    'data-smart-display'      => false,
    
    'data-unique-id'          => 'op_id',
    'data-id-field'           => 'op_id',
    'data-select-item-name'   => 'id[]',
    'data-query-params-type'  => 'params',
    'data-query-params'       => 'daicuo.table.query',
    'data-sort-name'          => 'op_order',
    'data-sort-order'         => 'asc',
    'data-sort-class'         => 'table-active',
    'data-sort-stable'        => 'true',
    
    'data-side-pagination'    => 'server',
    //'data-total-field'      => 'total',
    //'data-data-field'       => 'data',
    
    'data-pagination'         => false,
    //'data-page-number'      => $page,
    //'data-page-size'        => '30',
    //'data-page-list'        => '[10, 25, 50, 100]',
    
    //'data-pagination-h-align'        => 'left',
    //'data-pagination-detail-h-align' => 'right',
    //'data-pagination-v-align'        => 'top',
    //'data-show-extended-pagination'  => 'true',
    
    'columns'                 => $columns,
])}
</form>
{/block}