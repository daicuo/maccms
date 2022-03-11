//定义js语言包 不需要dom加载完毕
$.extend(daicuo.lang, {
    ajaxError:      "請求失敗",
    confirm:        "重要操作，不能恢復，請再次確認",
	close:          "關閉",
	loading:        "<i class='fa fa-lg fa-spinner mr-2'></i>正在加載",
	modalTitle:     "系統提示",
    preview:        "預覽",
    //日期选择器
    dateTime: {
      tooltips: {
        today: '今天',
        clear: '清除',
        close: '關閉',
        selectMonth: '選擇月份',
        prevMonth: '上個月',
        nextMonth: '下個月',
        selectYear: '選擇年份',
        prevYear: '上一年',
        nextYear: '下一看',
        selectDecade: '選擇日期',
        prevDecade: '上個年代',
        nextDecade: '下個年代',
        prevCentury: '上個世紀',
        nextCentury: '下個世紀',
        incrementHour: '增加一小時',
        pickHour: '選擇小時',
        decrementHour:'減小一小時',
        incrementMinute: '增加一分鍾',
        pickMinute: '選擇分',
        decrementMinute:'減少一分鍾',
        incrementSecond: '增加一秒',
        pickSecond: '選擇秒',
        decrementSecond:'減少一秒'
      }
    },
    //表格语言包
    bootstrapTable: {
	  formatLoadingMessage: function formatLoadingMessage() {
	    return '正在努力地載入資料，請稍候';
	  },
	  formatRecordsPerPage: function formatRecordsPerPage(pageNumber) {
	    return "\u6BCF\u9801\u986F\u793A ".concat(pageNumber, " \u9805\u8A18\u9304");
	  },
	  formatShowingRows: function formatShowingRows(pageFrom, pageTo, totalRows, totalNotFiltered) {
	    if (totalNotFiltered !== undefined && totalNotFiltered > 0 && totalNotFiltered > totalRows) {
	      return "\u986F\u793A\u7B2C ".concat(pageFrom, " \u5230\u7B2C ").concat(pageTo, " \u9805\u8A18\u9304\uFF0C\u7E3D\u5171 ").concat(totalRows, " \u9805\u8A18\u9304\uFF08\u5F9E ").concat(totalNotFiltered, " \u7E3D\u8A18\u9304\u4E2D\u904E\u6FFE\uFF09");
	    }

	    return "\u986F\u793A\u7B2C ".concat(pageFrom, " \u5230\u7B2C ").concat(pageTo, " \u9805\u8A18\u9304\uFF0C\u7E3D\u5171 ").concat(totalRows, " \u9805\u8A18\u9304");
	  },
	  formatSRPaginationPreText: function formatSRPaginationPreText() {
	    return '上一頁';
	  },
	  formatSRPaginationPageText: function formatSRPaginationPageText(page) {
	    return "\u7B2C".concat(page, "\u9801");
	  },
	  formatSRPaginationNextText: function formatSRPaginationNextText() {
	    return '下一頁';
	  },
	  formatDetailPagination: function formatDetailPagination(totalRows) {
	    return "\u7E3D\u5171 ".concat(totalRows, " \u9805\u8A18\u9304");
	  },
	  formatClearSearch: function formatClearSearch() {
	    return '清空過濾';
	  },
	  formatSearch: function formatSearch() {
	    return '搜尋';
	  },
	  formatNoMatches: function formatNoMatches() {
	    return '沒有找到符合的結果';
	  },
	  formatPaginationSwitch: function formatPaginationSwitch() {
	    return '隱藏/顯示分頁';
	  },
	  formatPaginationSwitchDown: function formatPaginationSwitchDown() {
	    return '顯示分頁';
	  },
	  formatPaginationSwitchUp: function formatPaginationSwitchUp() {
	    return '隱藏分頁';
	  },
	  formatRefresh: function formatRefresh() {
	    return '重新整理';
	  },
	  formatToggle: function formatToggle() {
	    return '切換';
	  },
	  formatToggleOn: function formatToggleOn() {
	    return '顯示卡片視圖';
	  },
	  formatToggleOff: function formatToggleOff() {
	    return '隱藏卡片視圖';
	  },
	  formatColumns: function formatColumns() {
	    return '列';
	  },
	  formatColumnsToggleAll: function formatColumnsToggleAll() {
	    return '切換所有';
	  },
	  formatFullscreen: function formatFullscreen() {
	    return '全屏';
	  },
	  formatAllRows: function formatAllRows() {
	    return '所有';
	  },
	  formatAutoRefresh: function formatAutoRefresh() {
	    return '自動刷新';
	  },
	  formatExport: function formatExport() {
	    return '導出數據';
	  },
	  formatJumpTo: function formatJumpTo() {
	    return '跳轉';
	  },
	  formatAdvancedSearch: function formatAdvancedSearch() {
	    return '高級搜尋';
	  },
	  formatAdvancedCloseButton: function formatAdvancedCloseButton() {
	    return '關閉';
	  }
	}
});