//定义js语言包 不需要dom加载完毕
$.extend(daicuo.lang, {
    ajaxError:      "请求失败",
    confirm:        "重要操作，不能恢复，请再次确认",
	close:          "关闭",
	loading:        "<i class='fa fa-lg fa-spinner mr-2'></i>正在加载",
	modalTitle:     "系统提示",
    preview:        "预览",
    //日期选择器
    dateTime: {
      tooltips: {
        today: '今天',
        clear: '清除',
        close: '关闭',
        selectMonth: '选择月份',
        prevMonth: '上个月',
        nextMonth: '下个月',
        selectYear: '选择年份',
        prevYear: '上一年',
        nextYear: '下一年',
        selectDecade: '选择时期',
        prevDecade: '上个年代',
        nextDecade: '下个年代',
        prevCentury: '上个世纪',
        nextCentury: '下个世纪',
        incrementHour: '增加一小时',
        pickHour: '选择小时',
        decrementHour:'减少一小时',
        incrementMinute: '增加一分钟',
        pickMinute: '选择分',
        decrementMinute:'减少一分钟',
        incrementSecond: '增加一秒',
        pickSecond: '选择秒',
        decrementSecond:'减少一秒'
      }
    },
    //表格语言包
    bootstrapTable: {
	  formatLoadingMessage: function formatLoadingMessage() {
	    return '正在努力地加载数据中，请稍候';
	  },
	  formatRecordsPerPage: function formatRecordsPerPage(pageNumber) {
	    return "\u6BCF\u9875\u663E\u793A ".concat(pageNumber, " \u6761\u8BB0\u5F55");
	  },
	  formatShowingRows: function formatShowingRows(pageFrom, pageTo, totalRows, totalNotFiltered) {
	    if (totalNotFiltered !== undefined && totalNotFiltered > 0 && totalNotFiltered > totalRows) {
	      return "\u663E\u793A\u7B2C ".concat(pageFrom, " \u5230\u7B2C ").concat(pageTo, " \u6761\u8BB0\u5F55\uFF0C\u603B\u5171 ").concat(totalRows, " \u6761\u8BB0\u5F55\uFF08\u4ECE ").concat(totalNotFiltered, " \u603B\u8BB0\u5F55\u4E2D\u8FC7\u6EE4\uFF09");
	    }
	    return "\u663E\u793A\u7B2C ".concat(pageFrom, " \u5230\u7B2C ").concat(pageTo, " \u6761\u8BB0\u5F55\uFF0C\u603B\u5171 ").concat(totalRows, " \u6761\u8BB0\u5F55");
	  },
	  formatSRPaginationPreText: function formatSRPaginationPreText() {
	    return '上一页';
	  },
	  formatSRPaginationPageText: function formatSRPaginationPageText(page) {
	    return "\u7B2C".concat(page, "\u9875");
	  },
	  formatSRPaginationNextText: function formatSRPaginationNextText() {
	    return '下一页';
	  },
	  formatDetailPagination: function formatDetailPagination(totalRows) {
	    return "\u603B\u5171 ".concat(totalRows, " \u6761\u8BB0\u5F55");
	  },
	  formatClearSearch: function formatClearSearch() {
	    return '清空过滤';
	  },
	  formatSearch: function formatSearch() {
	    return '搜索';
	  },
	  formatNoMatches: function formatNoMatches() {
	    return '没有找到匹配的记录';
	  },
	  formatPaginationSwitch: function formatPaginationSwitch() {
	    return '隐藏/显示分页';
	  },
	  formatPaginationSwitchDown: function formatPaginationSwitchDown() {
	    return '显示分页';
	  },
	  formatPaginationSwitchUp: function formatPaginationSwitchUp() {
	    return '隐藏分页';
	  },
	  formatRefresh: function formatRefresh() {
	    return '刷新';
	  },
	  formatToggle: function formatToggle() {
	    return '切换';
	  },
	  formatToggleOn: function formatToggleOn() {
	    return '显示卡片视图';
	  },
	  formatToggleOff: function formatToggleOff() {
	    return '隐藏卡片视图';
	  },
	  formatColumns: function formatColumns() {
	    return '列';
	  },
	  formatColumnsToggleAll: function formatColumnsToggleAll() {
	    return '切换所有';
	  },
	  formatFullscreen: function formatFullscreen() {
	    return '全屏';
	  },
	  formatAllRows: function formatAllRows() {
	    return '所有';
	  },
	  formatAutoRefresh: function formatAutoRefresh() {
	    return '自动刷新';
	  },
	  formatExport: function formatExport() {
	    return '导出数据';
	  },
	  formatJumpTo: function formatJumpTo() {
	    return '跳转';
	  },
	  formatAdvancedSearch: function formatAdvancedSearch() {
	    return '高级搜索';
	  },
	  formatAdvancedCloseButton: function formatAdvancedCloseButton() {
	    return '关闭';
	  }
	}
});