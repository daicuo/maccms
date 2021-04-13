{extend name="apps/common/view/admin.tpl" /}
<!-- -->
{block name="header_meta"}
<title>{:lang("apply_store")}－{:lang('appName')}</title>
{/block}
<!-- -->
{block name="main"}
<h6 class="border-bottom pb-2 text-purple">
  {:lang("apply_store")}
</h6>
<div class="toolbar mb-2" id="toolbar">
  <form class="form-inline" action="{:DcUrl('admin/category/delete','','')}" method="post" data-toggle="form">
    <div class="form-group mr-1">
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
    <div class="form-group mr-1">
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
    <div class="form-group mr-1">
      <input class="form-control form-control-sm" type="text" name="searchText" id="searchText" value="{$query.searchText|DcHtml}" placeholder="{:lang('keyword')}">
    </div>
    <div class="form-group">
      <button type="button" class="btn btn-purple btn-sm queryButton">{:lang('query')}</button>
    </div>
  </form>
</div>
{:DcBuildTable([
    //'data-escape'           => 'true',
    'data-toggle'             => 'bootstrap-table',
    'data-url'                => DcUrl('admin/store/index', '', ''),
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
    'data-query-params'       => 'queryParams',
    'data-sort-name'          => 'id',
    'data-sort-order'         => 'desc',
    //'data-sort-class'       => 'table-active',
    //'data-sort-stable'      => 'false',
    
    //'data-side-pagination'  => 'server',
    //'data-total-field'      => 'total',
    //'data-data-field'       => 'data',
    
    'data-page-number'        => $page,
    'data-page-size'          => '30',
    'data-page-list'          => [],
    
    'data-pagination'                  => 'true',
    //'data-pagination-h-align'        => 'left',
    //'data-pagination-detail-h-align' => 'right',
    //'data-pagination-v-align'        => 'top',
    //'data-show-extended-pagination'  => 'true',

    'columns'=>[
        [
            'data-field'=>'id',
            'data-title'=>'id',
            'data-width'=>'3',
            'data-width-unit'=>'%',
            'data-sortable'=>'true',
            'data-sort-name'=>'id',
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
            'data-field'=>'name',
            'data-title'=>lang('name'),
            'data-width'=>'15',
            'data-width-unit'=>'%',
            'data-align'=>'left',
            'data-halign'=>'center',
        ],
        
        [
            'data-field'=>'info',
            'data-title'=>lang('info'),
            'data-align'=>'left',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'version',
            'data-title'=>lang('version'),
            'data-width'=>'6',
            'data-width-unit'=>'%',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'',
        ],
        [
            'data-field'=>'view',
            'data-title'=>lang('down'),
            'data-width'=>'6',
            'data-width-unit'=>'%',
            'data-sortable'=>'true',
            'data-sort-name'=>'view',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'score',
            'data-title'=>lang('score'),
            'data-width'=>'6',
            'data-width-unit'=>'%',
            'data-sortable'=>'true',
            'data-sort-name'=>'score',
            'data-sort-order'=>'desc',
            'data-align'=>'center',
            'data-halign'=>'center',
        ],
        [
            'data-field'=>'operate',
            'data-title'=>lang('operate'),
            'data-width'=>'30',
            'data-width-unit'=>'%',
            'data-align'=>'center',
            'data-halign'=>'center',
            'data-formatter'=>'',
        ]
    ]
])}
{/block}
<!-- -->
{block name="js"}
<script>
//刷新查询
var queryParams = function queryParams(params){
   var param = { 
     pageNumber: params.pageNumber, 
     pageSize: params.pageSize,
     sortName: params.sortName,
     sortOrder: params.sortOrder,
     searchText: $("#searchText").val(),
     termId: $("#term_id").val(),
     price: $("#price").val()
    }; 
    return param; 
};
$(document).on('click', ".queryButton",function(){
   $('table[data-toggle="bootstrap-table"]').bootstrapTable('refresh');
});
</script>
{/block}