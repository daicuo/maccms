<?php
namespace app\common\widget;

use think\Controller;

class Table extends Controller
{

    public function build($args=[])
    {
        $table = array();
        //$table['columns'] = [];
        $table['data-toggle'] = 'bootstrap-table';
		//$table['data-locale'] = str_replace(['zh-cn','zh-tw'], ['zh-CN','zh-TW'], config('default_lang'));
        $table['data-classes'] = 'table table-bordered table-hover table-striped text-nowrap';//table-dark table-sm table-borderless
        $table['data-thead-classes'] = '';//thead-light thead-dark
        $table['data-data'] = '';
        $table['data-url'] = '';
        $table['data-method'] = 'get';
        $table['data-cache'] = 'false';
        $table['data-content-type'] = 'application/json';
        $table['data-data-type'] = 'json';
        $table['data-undefined-text'] = '-';
        //
        //$table['data-ajax'] = '';//(function)
        //$table['data-ajax-options'] = '{}';//(object)
        //$table['data-query-params'] = 'queryParams';//function请求远程数据时发送其他参数 (pageSize, pageNumber, searchText, sortName, sortOrder)
        $table['data-query-params-type'] = 'params';//'limit'时为发送具有RESTFul类型的查询参数,上条参数则为(limit, offset, search, sort, order)
        //$table['data-response-handler'] = 'function(){}';//function响应数据
        //
        $table['data-toolbar'] = '.toolbar';
        $table['data-toolbar-align'] = 'left';//left right
        //$table['data-buttons-toolbar'] = '';//自定义按钮工具栏 .buttons-toolbar
        //$table['data-buttons-align'] = 'right';//如何对齐工具栏按钮 left,right
        //$table['data-buttons-prefix'] = 'btn';//表格按钮的前缀
        //$table['data-buttons-class'] = 'secondary';//'btn-'表格按钮的类（在后面添加）
        $table['data-show-header'] = 'true';//设置false 为隐藏表格标题
        $table['data-show-footer'] = 'false';//设置true 为显示摘要页脚行
        //$table['data-footer-style'] = 'footerStyle';//(function)页脚样式格式化程序函数
        $table['data-show-columns'] = 'false';//设置true为显示列下拉列表
        $table['data-show-columns-toggle-all'] = 'false';//设置true为在列选项/下拉列表中显示“全部切换”复选框
        $table['data-show-columns-search'] = 'false';//设置true为显示对列过滤器的搜索
        $table['data-minimum-count-columns'] = '1';//从列下拉列表中隐藏的最小列数
        $table['data-show-pagination-switch'] = 'false';//设置true为显示分页开关按钮
        $table['data-show-refresh'] = 'false';//设置true为显示刷新按钮
        $table['data-show-toggle'] = 'false';//设置true显示切换按钮以切换表格/卡片视图
        $table['data-show-fullscreen'] = 'false';//设置true显示全屏按钮
        $table['data-smart-display'] = 'true';//设置true为智能显示分页或名片视图
        $table['data-escape'] = 'true';//设置true为转义用于插入HTML的字符串
        //$table['data-filter-options'] = '{ filterAlgorithm: \'and\' }';
        //$table['data-id-field'] = '';//指明哪个字段将用作复选框/单选框值
        //$table['data-select-item-name'] = 'btSelectItem';//单选或复选框输入的名称
        $table['data-click-to-select'] = 'false';//设置true 为在单击行时选择复选框或单选框
        //$table['data-ignore-click-to-select-on'] = '{ return ['A', 'BUTTON'].includes(tagName) }';//(function)
        $table['data-single-select'] = 'false';//设置true 为允许复选框仅选择一行
        $table['data-checkbox-header'] = 'true';//设置false为隐藏标题行中的所有复选框
        //$table['data-maintain-meta-data'] = 'false';//设置true为在更改页面和搜索上维护以下元数据(选中的行与隐藏的行)
        //$table['data-multiple-select-row'] = 'false';//设置true以启用多选行。可以使用ctrl键单击以选择一行，或使用shift键单击以选择一系列行
        //$table['data-unique-id'] = '';//为每一行指示唯一的标识符
        $table['data-card-view'] = 'false';//设置true 为显示名片视图表，例如移动视图
        $table['data-detail-view'] = 'false';//设置true为显示详细视图表
        $table['data-detail-view-icon'] = 'true';//设置true为显示详细信息视图列（加/减图标）
        $table['data-detail-view-by-click'] = 'false';//设置true单击以设置切换细节视图
        //$table['data-detail-formatter'] = 'function';//(function)当格式化您的详细信息视图detailView 设置为 true时函数处理
        //$table['data-detail-filter'] = 'function';//(function)当启用每行扩展detailView 设置到 true做函数处理
        //$table['data-icons'] = "{paginationSwitchDown: 'fa-caret-down',paginationSwitchUp: 'fa-caret-up',refresh: 'fa-refresh',toggleOff: 'fa-toggle-off',toggleOn: 'fa-toggle-on',columns: 'fa-th-list',fullscreen: 'fa-arrows-alt',detailOpen: 'fa-plus',detailClose: 'fa-minus'}";
        //$table['data-icon-size'] = 'sm';//lg sm
        $table['data-icons-prefix'] = 'fa';//图标前缀
        //
        $table['data-search'] = 'false';
        $table['data-search-on-enter-key'] = 'false';
        $table['data-strict-search'] = 'false';//启用严格搜索
        $talbe['visible-search'] = 'false';//设置true 为仅在可见列/数据中搜索，如果数据包含其他未显示的值，则在搜索时将忽略它们
        $table['show-button-icons'] = 'true';
        $table['show-button-text'] = 'false';
        $table['data-show-search-button'] = 'false';//设置true 为在搜索输入后面显示搜索按钮。仅在按下按钮时才会执行搜索
        $table['data-show-search-clear-button'] = 'false';//设置true为在搜索输入后面显示一个清除按钮，该按钮将清除搜索输入
        $table['data-trim-on-search'] = 'true';//设置true 为修剪搜索字段中的空格
        $table['data-search-align'] = 'right';//指示如何对齐搜索输入。'left', 'right' 可以使用
        $table['data-search-time-out'] = '500';//搜索超时
        $table['data-search-text'] = '';//设置搜索属性后，初始化搜索文本
        //$table['data-custom-search'] = 'customSearch';//(function)定义搜索功能
        //
        $table['data-sortable'] = 'true';//设置 false为禁用所有列的可排序。
        //$table['data-sort-name'] = 'undefined';//默认排序字段
        $table['data-sort-class'] = 'table-active';//被排序的td类
        $table['data-sort-order'] = 'asc';//定义列的排序顺序，只能是'asc' 或 'desc'.
        $table['data-sort-stable'] = 'false';//设置 true以获得稳定的排序。我们将'_position' 属性添加到该行。
        $table['data-remember-order'] = 'false';//设置true 为记住每列的顺序。
        $table['data-silent-sort'] = 'true';//(server)loading...
        $table['data-server-sort'] = true;//(server)设置false为在客户端对数据进行排序
        //$table['data-custom-sort'] = '';//(function)自定义排序功能
        //
        $table['data-pagination'] = 'false';//是否分页
        $table['data-side-pagination'] = 'client';//分页方式(client,server)
        $table['data-only-info-pagination'] = 'false';//分页模式下只返回当前面的数据(总100条也只显示默认的第1页的10条)
        $table['data-show-extended-pagination'] = 'false';//分页高级样式
        $table['data-pagination-loop'] = 'true';//分页连续循环模式
        $table['data-total-field'] = 'total';//(server)服务端总记录数
        $table['data-data-field'] = 'rows';//(server)服务端数据列表
        $table['data-total-not-filtered-field'] = 'totalNotFiltered';//(server)服务端总记录数不包含过滤(高级分页模式下有效)
        $table['data-page-number'] = '1';
        $table['data-page-size'] = '10';
        $table['data-page-list'] = 'all';//default [10, 25, 50, 100]
        $table['data-pagination-h-align'] = 'right';//left,right
        $table['data-pagination-v-align'] = 'bottom';//top,bottom,both
        $table['data-pagination-detail-h-align'] = 'left';//left,right
        $table['data-pagination-pre-text'] = '‹';
        $table['data-pagination-next-text'] = '›';
        $table['data-pagination-successively-size'] = '5';//最大连续页数
        $table['data-pagination-pages-by-side'] = '1';//当前页面每侧（右侧，左侧）的页数
        //预留钩子
        \think\Hook::listen('table_build', $args);
        //合并参数并赋值模板变量
        $this->assign('table', array_merge($table, $args));
        //释放内存
        unset($table);unset($args);
        //模板渲染
        return $this->fetch('common@table/index');
    }	
}