{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("admin/store/index")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("admin/store/index")}
</h6>
<div class="toolbar" id="toolbar">
  <form class="form-inline mb-0" action="{:DcUrl('admin/store/delete')}" method="post" data-toggle="form">
    <div class="form-group mb-0 mr-1">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <span class="input-group-text">{:lang('category')}</span>
        </div>
        <select class="form-control" name="term_id" id="term_id">
          <option value="">{:lang('all')}</option>
          {volist name="category" id="dc"}
          <option value="{$dc.term_id}">{$dc.term_name}</option>
          {/volist}
        </select>
      </div>
    </div>
    <div class="form-group mb-0 mr-1">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <span class="input-group-text">{:lang('score')}</span>
        </div>
        <select class="form-control" name="price" id="price">
          <option value=""><i class="fa fa-fw fa-list"></i> {:lang('all')}</option>
          <option value="free"><i class="fa fa-fw fa-gift"></i> {:lang('free')}</option>
          <option value="pay"><i class="fa fa-fw fa-rmb"></i> {:lang('pay')}</option>
        </select>
      </div>
    </div>
    <div class="form-group mb-0 mr-1">
      <a class="btn btn-sm btn-purple" href="javascript:;" data-toggle="refresh">
        <i class="fa fa-refresh"></i> {:lang('refresh')}
      </a>
    </div>
  </form>
</div>
{:DcBuildTable([
    'data-name'               => 'admin/apply/store',
    'data-escape'             => 'false',
    'data-toggle'             => 'bootstrap-table',
    'data-url'                => DcUrl('admin/store/index'),
    'data-buttons-prefix'     => 'btn',
    'data-buttons-class'      => 'purple',
    'data-icon-size'          => 'sm',
    
    'data-toolbar'            => '.toolbar',
    'data-toolbar-align'      => 'none float-md-left',
    'data-buttons-align'      => 'right',
    'data-search-align'       => 'none float-md-right',
    'data-search-text'        => $query['searchText'],
    'data-search'             => true,
    'data-show-search-button' => true,
    'data-show-refresh'       => false,
    'data-show-toggle'        => true,
    'data-show-fullscreen'    => true,
    'data-show-button-text'   => false,
    'data-smart-display'      => false,
    
    'data-unique-id'          => 'id',
    'data-id-field'           => 'id',
    'data-select-item-name'   => 'id[]',
    'data-query-params-type'  => 'params',
    'data-query-params'       => 'daicuo.admin.store.query',
    'data-sort-name'          => 'view',
    'data-sort-order'         => 'desc',
    'data-sort-class'         => 'table-active',
    'data-sort-stable'        => 'false',
    
    'data-side-pagination'    => 'server',
    'data-total-field'        => 'total',
    'data-data-field'         => 'list',

    'data-pagination'         => false,
    'data-page-number'        => $page,
    'data-page-size'          => 50,
    'data-page-list'          => '[50, 100, 200]',

    'columns'=>[
        [
            'data-field'      => 'id',
            'data-title'      => 'id',
            'data-class'      => '',
            'data-align'      => 'center',
            'data-valign'     => 'middle',
            'data-halign'     => 'center',
            'data-falign'     => 'center',
            'data-visible'    => true,
            'data-width'      => '60',
            'data-sortable'   => true,
            'data-sort-name'  => 'id',
            'data-sort-order' => 'asc',
        ],
        [
            'data-field'      => 'name',
            'data-title'      => lang('name'),
            'data-align'      => 'left',
            'data-halign'     => 'center',
        ],
        [
            'data-field'      => 'module',
            'data-title'      => lang('module'),
            'data-align'      => 'center',
            'data-halign'     => 'center',
            'data-width'      => '120',
        ],
        [
            'data-field'      => 'version',
            'data-title'      => lang('version'),
            'data-align'      => 'center',
            'data-halign'     => 'center',
            'data-width'      => '80',
        ],
        [
            'data-field'      => 'score',
            'data-title'      => lang('score'),
            'data-align'      => 'center',
            'data-halign'     => 'center',
            'data-width'      => '80',
            'data-sortable'   => true,
            'data-sort-name'  => 'score',
            'data-sort-order' => 'desc',
        ],
        [
            'data-field'      => 'view',
            'data-title'      => lang('down'),
            'data-align'      => 'center',
            'data-halign'     => 'center',
            'data-width'      => '80',
            'data-sortable'   => true,
            'data-sort-name'  => 'view',
            'data-sort-order' => 'desc',
        ],
        [
            'data-field'      => 'info',
            'data-title'      => lang('info'),
            'data-align'      => 'left',
            'data-halign'     => 'center',
        ],
        [
            'data-field'      => 'demo',
            'data-title'      => lang('demo'),
            'data-align'      => 'center',
            'data-halign'     => 'center',
            'data-width'      => '60',
        ],
        [
            'data-field'      => 'operate',
            'data-title'      => lang('operate'),
            'data-align'      => 'center',
            'data-halign'     => 'center',
            'data-width'      => '140',
            'data-escape'     => false,
        ]
    ]
])}
{/block}