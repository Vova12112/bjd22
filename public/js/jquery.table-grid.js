;(function (window, $, undefined) {

	const
		TABLE_GRID = "TableGrid",
		SORT_ORDER_ASC = "asc",
		SORT_ORDER_DESC = "desc",
		FIRST_PLACEHOLDER = "{first}",
		PAGES_PLACEHOLDER = "{pages}",
		PREV_PLACEHOLDER = "{prev}",
		NEXT_PLACEHOLDER = "{next}",
		LAST_PLACEHOLDER = "{last}",
		PAGE_INDEX_PLACEHOLDER = "{pageIndex}",
		PAGE_COUNT_PLACEHOLDER = "{pageCount}",
		ITEM_COUNT_PLACEHOLDER = "{itemCount}",
		FROM_ITEM_PLACEHOLDER = "{fromItem}",
		TO_ITEM_PLACEHOLDER = "{toItem}",
		REFRESH_PAGE_PLACEHOLDER = "{refresh}";

	let getOrApply = function (value, context) {
		if ($.isFunction(value)) {
			return value.apply(context, $.makeArray(arguments).slice(2));
		}
		return value;
	};

	let normalizePromise = function (promise) {
		let d = $.Deferred();
		if (promise && promise.then) {
			promise.then(function () {
				d.resolve.apply(d, arguments);
			}, function () {
				d.reject.apply(d, arguments);
			});
		} else {
			d.resolve(promise);
		}
		return d.promise();
	};

	function Grid(element, config) {
		this._container = $(element).data(TABLE_GRID, this);
		this.data = [];
		this.fields = [];
		this._sortField = null;
		this._sortOrder = SORT_ORDER_ASC;
		this._firstDisplayingPage = 1;
		this._init(config);
		this.render();
	}

	Grid.prototype = {
		width         : "100%",
		height        : "auto",
		updateOnResize: true,

		rowClass   : $.noop,
		rowRenderer: null,

		loadDataUrl     : null,
		rowClickUrl     : null,
		rowClickEntityId: null,

		rowClick: function (args) {
			if (this.rowClickUrl === 'function'){
				return this.rowClickFunction(args);
			}
			if (this.rowClickUrl !== null && this.rowClickEntityId !== null) {
				let data = {};
				data[this.rowClickEntityId] = $("<div>").append(args.item.id).text().trim();
				ajaxPopupRequest(this.rowClickUrl, data);
			} else if (args.item.link !== undefined && args.item.link !== null) {
				if (args.item.link !== '') {
					window.open(args.item.link, '_blank');
				} else if (args.item.empty_link !== undefined && args.item.empty_link !== "") {
					notification.show(args.item.empty_link, "info")
				}
			}
		},

		noDataContent : "Not found",
		noDataRowClass: "nodata-row",

		heading          : true,
		headerRowRenderer: null,
		headerRowClass   : "header-row",
		headerCellClass  : "header-cell",

		selecting       : true,
		selectedRowClass: "selected-row",
		oddRowClass     : "row",
		evenRowClass    : "alt-row",
		cellClass       : "cell",

		sorting      : true,
		sortableClass: "header-sortable",
		sortAscClass : "header-sort header-sort-asc",
		sortDescClass: "header-sort header-sort-desc",

		searchSelector: null,

		paging                     : true,
		pagerContainer             : null,
		pageLoading                : true,
		pageIndex                  : 1,
		pageSize                   : 10,
		pageButtonCount            : 1,
		pagerFormat                : "{fromItem} {toItem} {itemCount} &nbsp; {first} {prev} {refresh} {next}",
		pagePrevText               : "<i class='paginator prev'></i>",
		pageNextText               : "<i class='paginator next'></i>",
		pageFirstText              : "<i class='paginator first'></i>",
		pageRefresh                : "<i class='paginator refresh'></i>",
		pageLastText               : "Last",
		pageNavigatorNextText      : "...",
		pageNavigatorPrevText      : "...",
		pagerContainerClass        : "pager-container",
		pagerClass                 : "pager",
		pagerNavButtonClass        : "pager-nav-button",
		pagerNavButtonInactiveClass: "pager-nav-inactive-button",
		pageClass                  : "pager-page",
		currentPageClass           : "pager-current-page",

		autoload: false,

		onInit          : $.noop,
		onRefreshing    : $.noop,
		onRefreshed     : $.noop,
		onPageChanged   : $.noop,
		onItemInvalid   : $.noop,
		onDataLoading   : $.noop,
		onDataLoaded    : $.noop,
		onOptionChanging: $.noop,
		onOptionChanged : $.noop,
		onError         : $.noop,

		invalidClass   : "invalid",
		containerClass : "table-grid",
		tableClass     : "table",
		gridHeaderClass: "grid-header",
		gridBodyClass  : "grid-body",

		_init: function (config) {
			$.extend(this, config);
			this._initLoadStrategy();
			this._initLoadDataController(this.loadDataUrl, this.searchSelector);
			this._initFields();
			this._attachWindowLoadResize();
			this._attachWindowResizeCallback();
			this._callEventHandler(this.onInit)
		},

		loadStrategy: function () {
			return new jTable.loadStrategies.PageLoadingStrategy(this);
		},

		_initLoadStrategy: function () {
			this._loadStrategy = getOrApply(this.loadStrategy, this);
		},

		_initLoadDataController: function (loadDataUrl, searchSelector) {
			if (loadDataUrl !== null) {
				this._controller = {
					loadData: function (args) {
						let
							data = {
								"current_page": args.pageIndex,
								"page_size"   : args.pageSize,
								"sort_field"  : args.sortField,
								"sort_order"  : args.sortOrder,
							};

						if (searchSelector !== null) {
							let $search = $(searchSelector),
								$filters = $search.closest(".js-search-container").find(".js-filters-section");

							data.search = $search.val();
							if ($filters !== null && $filters !== undefined) {
								data.filters = {};
								$filters.find("select").each(function () {
									let value = $(this).val();
									if (value !== "" && value !== "-" && value !== undefined && value !== null) {
										data.filters[$(this).prop("name")] = value;
									}
								});
							}
						}
						return ajaxRequest(loadDataUrl, "POST", "json", data);
					}
				};
			}
		},

		renderTemplate: function (source, context, config) {
			let args = [];
			for (let key in config) {
				args.push(config[key]);
			}
			args.unshift(source, context);
			source = getOrApply.apply(null, args);
			return (source === undefined || source === null) ? "" : source;
		},

		_initFields: function () {
			let self = this;
			self.fields = $.map(self.fields, function (field) {
				if ($.isPlainObject(field)) {
					let fieldConstructor = (field.type && jTable.fields[field.type]) || jTable.Field;
					field = new fieldConstructor(field);
				}
				field._grid = self;
				return field;
			});
		},

		_attachWindowLoadResize: function () {
			$(window).on("load", $.proxy(this._refreshSize, this));
		},

		_attachWindowResizeCallback: function () {
			if (this.updateOnResize) {
				$(window).on("resize", $.proxy(this._refreshSize, this));
			}
		},

		_detachWindowResizeCallback: function () {
			$(window).off("resize", this._refreshSize);
		},

		option: function (key, value) {
			let optionChangingEventArgs,
				optionChangedEventArgs;

			if (arguments.length === 1) {
				return this[key];
			}
			optionChangingEventArgs = {
				option  : key,
				oldValue: this[key],
				newValue: value
			};
			this._callEventHandler(this.onOptionChanging, optionChangingEventArgs);
			this._handleOptionChange(optionChangingEventArgs.option, optionChangingEventArgs.newValue);
			optionChangedEventArgs = {
				option: optionChangingEventArgs.option,
				value : optionChangingEventArgs.newValue
			};
			this._callEventHandler(this.onOptionChanged, optionChangedEventArgs);
		},

		_handleOptionChange: function (name, value) {
			this[name] = value;
			switch (name) {
				case "width":
				case "height":
					this._refreshSize();
					break;
				case "rowClass":
				case "rowRenderer":
				case "rowClick":
				case "noDataRowClass":
				case "noDataContent":
				case "selecting":
				case "selectedRowClass":
				case "oddRowClass":
				case "evenRowClass":
					this._refreshContent();
					break;
				case "pageButtonCount":
				case "pagerFormat":
				case "pagePrevText":
				case "pageNextText":
				case "pageFirstText":
				case "pageLastText":
				case "pageNavigatorNextText":
				case "pageNavigatorPrevText":
				case "pagerClass":
				case "pagerNavButtonClass":
				case "pageClass":
				case "currentPageClass":
				case "pagerRenderer":
					this._refreshPager();
					break;
				case "fields":
					this._initFields();
					this.render();
					break;
				case "data":
				case "heading":
				case "paging":
					this.refresh();
					break;
				case "loadStrategy":
				case "pageLoading":
					this._initLoadStrategy();
					this.search();
					break;
				case "pageIndex":
					this.openPage(value);
					break;
				case "pageSize":
					this.refresh();
					this.search();
					break;
				case "updateOnResize":
					this._detachWindowResizeCallback();
					this._attachWindowResizeCallback();
					break;
				default:
					this.render();
					break;
			}
		},

		destroy: function () {
			this._detachWindowResizeCallback();
			this._clear();
			this._container.removeData(TABLE_GRID);
		},

		render: function () {
			this._renderGrid();
			return this.autoload ? this.loadData() : $.Deferred().resolve().promise();
		},

		_renderGrid: function () {
			this._clear();
			this._container.addClass(this.containerClass).css("position", "relative").append(this._createTable());
			this._pagerContainer = this._createPagerContainer();
			this.refresh();
		},

		_clear: function () {
			this._pagerContainer && this._pagerContainer.empty();
			this._container.empty().css({position: "", width: "", height: ""});
		},

		_createTable: function () {
			let $table = $("<table>").addClass(this.tableClass).css("width", this.width)
			return $table.append(this._createHeader()).append(this._createBody());
		},

		_createHeader: function () {
			if ($.isFunction(this.headerRowRenderer)) {
				return $(this.renderTemplate(this.headerRowRenderer, this));
			}
			let $thead = this._headerGrid = this._header = $("<thead/>");
			let $headerTr = this._headerRow = $("<tr>").addClass(this.headerRowClass);
			this._eachField(function (field, index) {
				let $th = this._prepareCell("<th>", field, "headercss", this.headerCellClass)
					.append(this.renderTemplate(field.headerTemplate, field))
					.appendTo($headerTr);

				if (this.sorting && field.sorting) {
					$th.addClass(this.sortableClass)
						.on("click", $.proxy(function () {
							this.sort(index);
						}, this));
				}
			});
			$headerTr.appendTo($thead);
			return $thead;
		},

		_createBody: function () {
			return this._content = this._bodyGrid = $("<tbody>");
		},

		_createPagerContainer: function () {
			let pagerContainer = this.pagerContainer || $("<div>").appendTo(this._container);
			return $(pagerContainer).addClass(this.pagerContainerClass);
		},

		_eachField: function (callBack) {
			let self = this;
			$.each(this.fields, function (index, field) {
				if (field.visible) {
					callBack.call(self, field, index);
				}
			});
		},

		_prepareCell: function (cell, field, cssprop, cellClass) {
			return $(cell)
				.css("width", field.width)
				.addClass(cellClass || this.cellClass)
				.addClass((cssprop && field[cssprop]) || field.css)
				.addClass(field.align ? ("align-" + field.align) : "");
		},

		_callEventHandler: function (handler, eventParams) {
			handler.call(this, $.extend(eventParams, {
				grid: this
			}));
			return eventParams;
		},

		reset: function () {
			this._resetSorting();
			this._resetPager();
			return this._loadStrategy.reset();
		},

		_resetPager: function () {
			this._firstDisplayingPage = 1;
			this._setPage(1);
		},

		_resetSorting: function () {
			this._sortField = null;
			this._sortOrder = SORT_ORDER_ASC;
			this._clearSortingCss();
		},

		refresh: function () {
			this._callEventHandler(this.onRefreshing);
			this._refreshHeading();
			this._refreshContent();
			this._refreshPager();
			this._refreshSize();
			this._callEventHandler(this.onRefreshed);
		},

		_refreshHeading: function () {
			this._headerRow.toggle(this.heading);
		},

		_refreshContent: function () {
			let $content = this._content;
			$content.empty();
			if (!this.data.length) {
				$content.append(this._createNoDataRow());
				return this;
			}
			let
				indexFrom = this._loadStrategy.firstDisplayIndex(),
				indexTo = this._loadStrategy.lastDisplayIndex();
			for (let itemIndex = indexFrom; itemIndex < indexTo; itemIndex++) {
				let item = this.data[itemIndex];
				$content.append(this._createRow(item, itemIndex));
			}
		},

		_createNoDataRow: function () {
			let amountOfFields = 0;
			this._eachField(function () {
				amountOfFields++;
			});
			return $("<tr>")
				.addClass(this.noDataRowClass)
				.append($("<td>")
					.addClass(this.cellClass).attr("colspan", amountOfFields)
					.append(this.renderTemplate(this.noDataContent, this)
					)
				);
		},

		_createRow: function (item, itemIndex) {
			let $result;
			if ($.isFunction(this.rowRenderer)) {
				$result = this.renderTemplate(this.rowRenderer, this, {item: item, itemIndex: itemIndex});
			} else {
				$result = $("<tr>");
				this._renderCells($result, item);
			}
			$result.addClass(this._getRowClasses(item, itemIndex))
				.data("TableItem", item)
				.on("click", $.proxy(function (e) {
					this.rowClick({
						item     : item,
						itemIndex: itemIndex,
						event    : e
					});
				}, this));

			if (this.selecting) {
				this._attachRowHover($result);
			}
			return $result;
		},

		_getRowClasses: function (item, itemIndex) {
			let classes = [];
			classes.push(((itemIndex + 1) % 2) ? this.oddRowClass : this.evenRowClass);
			classes.push(getOrApply(this.rowClass, this, item, itemIndex));
			return classes.join(" ");
		},

		_attachRowHover: function ($row) {
			let selectedRowClass = this.selectedRowClass;
			$row.hover(function () {
					$(this).addClass(selectedRowClass);
				},
				function () {
					$(this).removeClass(selectedRowClass);
				}
			);
		},

		_renderCells: function ($row, item) {
			this._eachField(function (field) {
				$row.append(this._createCell(item, field));
			});
			return this;
		},

		_createCell: function (item, field) {
			let fieldValue = this._getItemFieldValue(item, field);
			let args = {value: fieldValue, item: item};
			return this._prepareCell($("<td>").append(this.renderTemplate(field.itemTemplate || fieldValue, field, args)), field);
		},

		_getItemFieldValue: function (item, field) {
			let props = field.name.split('.');
			let result = item[props.shift()];
			while (result && props.length) {
				result = result[props.shift()];
			}
			return result;
		},

		sort: function (field, order) {
			if ($.isPlainObject(field)) {
				order = field.order;
				field = field.field;
			}
			this._clearSortingCss();
			this._setSortingParams(field, order);
			this._setSortingCss();
			return this._loadStrategy.sort();
		},

		_clearSortingCss: function () {
			this._headerRow.find("th").removeClass(this.sortAscClass).removeClass(this.sortDescClass);
		},

		_setSortingParams: function (field, order) {
			field = this._normalizeField(field);
			order = order || ((this._sortField === field) ? this._reversedSortOrder(this._sortOrder) : SORT_ORDER_ASC);
			this._sortField = field;
			this._sortOrder = order;
		},

		_normalizeField: function (field) {
			if ($.isNumeric(field)) {
				return this.fields[field];
			}
			if (typeof field === "string") {
				return $.grep(this.fields, function (f) {
					return f.name === field;
				})[0];
			}
			return field;
		},

		_reversedSortOrder: function (order) {
			return (order === SORT_ORDER_ASC ? SORT_ORDER_DESC : SORT_ORDER_ASC);
		},

		_setSortingCss: function () {
			let fieldIndex = this._visibleFieldIndex(this._sortField);
			this._headerRow.find("th").eq(fieldIndex).addClass(this._sortOrder === SORT_ORDER_ASC ? this.sortAscClass : this.sortDescClass);
		},

		_visibleFieldIndex: function (field) {
			return $.inArray(field, $.grep(this.fields, function (f) {
				return f.visible;
			}));
		},

		_sortData: function () {
			let sortFactor = this._sortFactor(),
				sortField = this._sortField;
			if (sortField) {
				this.data.sort(function (item1, item2) {
					return sortFactor * sortField.sortingFunc(item1[sortField.name], item2[sortField.name]);
				});
			}
		},

		_sortFactor: function () {
			return this._sortOrder === SORT_ORDER_ASC ? 1 : -1;
		},

		_itemsCount: function () {
			return this._loadStrategy.itemsCount();
		},

		_fromItem: function () {
			return this._loadStrategy.fromItem();
		},

		_toItem: function () {
			return this._loadStrategy.toItem();
		},

		_pagesCount: function () {
			let itemsCount = this._itemsCount(),
				pageSize = this.pageSize;
			return Math.floor(itemsCount / pageSize) + (itemsCount % pageSize ? 1 : 0);
		},

		_refreshPager: function () {
			let $pagerContainer = this._pagerContainer;
			$pagerContainer.empty();
			if (this.paging) {
				$pagerContainer.append(this._createPager());
			}
			let showPager = this.paging || this._pagesCount() > 1;
			$pagerContainer.toggle(showPager);
		},

		_createPager: function () {
			return $("<div>").append(this._createPagerByFormat()).addClass(this.pagerClass);
		},

		_createPagerByFormat: function () {
			let pageIndex = this.pageIndex,
				pageCount = this._pagesCount(),
				itemCount = this._itemsCount(),
				fromItem = this._fromItem(),
				toItem = this._toItem(),
				pagerParts = this.pagerFormat.split(" ");

			return $.map(pagerParts, $.proxy(function (pagerPart) {
				let result = pagerPart;
				if (pagerPart === PAGES_PLACEHOLDER) {
					result = this._createPages();
				} else if (pagerPart === FIRST_PLACEHOLDER) {
					result = this._createPagerNavButton(this.pageFirstText, 1, pageIndex > 1);
				} else if (pagerPart === PREV_PLACEHOLDER) {
					result = this._createPagerNavButton(this.pagePrevText, pageIndex - 1, pageIndex > 1);
				} else if (pagerPart === NEXT_PLACEHOLDER) {
					result = this._createPagerNavButton(this.pageNextText, pageIndex + 1, pageIndex < pageCount);
				} else if (pagerPart === LAST_PLACEHOLDER) {
					result = this._createPagerNavButton(this.pageLastText, pageCount, pageIndex < pageCount);
				} else if (pagerPart === PAGE_INDEX_PLACEHOLDER) {
					result = pageIndex;
				} else if (pagerPart === PAGE_COUNT_PLACEHOLDER) {
					result = pageCount;
				} else if (pagerPart === ITEM_COUNT_PLACEHOLDER) {
					result = itemCount === 0 ? "" : $("<span>").html(itemCount);
				} else if (pagerPart === FROM_ITEM_PLACEHOLDER) {
					result = fromItem === null || fromItem === undefined ? "" : $("<span>").html(fromItem + " - ");
				} else if (pagerPart === TO_ITEM_PLACEHOLDER) {
					result = toItem === null || toItem === undefined ? "" : $("<span>").html(toItem + " of ");
				} else if (pagerPart === REFRESH_PAGE_PLACEHOLDER) {
					result = this._createPagerNavButton(this.pageRefresh, pageIndex, true);
				}
				return $.isArray(result) ? result.concat([" "]) : [result, " "];
			}, this));
		},

		_createPages: function () {
			let pageCount = this._pagesCount(),
				pageButtonCount = this.pageButtonCount,
				firstDisplayingPage = this._firstDisplayingPage,
				pages = [];
			if (firstDisplayingPage > 1) {
				pages.push(this._createPagerPageNavButton(this.pageNavigatorPrevText, this.showPrevPages));
			}
			for (let i = 0, pageNumber = firstDisplayingPage; i < pageButtonCount && pageNumber <= pageCount; i++, pageNumber++) {
				pages.push(pageNumber === this.pageIndex ? this._createPagerCurrentPage() : this._createPagerPage(pageNumber));
			}
			if ((firstDisplayingPage + pageButtonCount - 1) < pageCount) {
				pages.push(this._createPagerPageNavButton(this.pageNavigatorNextText, this.showNextPages));
			}
			return pages;
		},

		_createPagerNavButton: function (text, pageIndex, isActive) {
			return this._createPagerButton(text, this.pagerNavButtonClass + (isActive ? "" : " " + this.pagerNavButtonInactiveClass),
				isActive ? function () {
					this.openPage(pageIndex);
				} : $.noop);
		},

		_createPagerPageNavButton: function (text, handler) {
			return this._createPagerButton(text, this.pagerNavButtonClass, handler);
		},

		_createPagerPage: function (pageIndex) {
			return this._createPagerButton(pageIndex, this.pageClass, function () {
				this.openPage(pageIndex);
			});
		},

		_createPagerButton: function (text, css, handler) {
			return $("<span>").addClass(css).append($("<a>").attr("href", "javascript:void(0);").html(text).on("click", $.proxy(handler, this)));
		},

		_createPagerCurrentPage: function () {
			return $("<span>").addClass(this.pageClass).addClass(this.currentPageClass).text(this.pageIndex);
		},

		_refreshSize: function () {
			this._refreshHeight();
			this._refreshWidth();
		},

		_refreshWidth: function () {
			let width = (this.width === "auto") ? this._getAutoWidth() : this.width;
			this._container.width(width);
		},

		_getAutoWidth: function () {
			let
				$headerGrid = this._headerGrid,
				$header = this._header;

			$headerGrid.width("auto");

			let
				contentWidth = $headerGrid.outerWidth(),
				borderWidth = $header.outerWidth() - $header.innerWidth();

			$headerGrid.width("");
			return contentWidth + borderWidth;
		},

		_refreshHeight: function () {
			let container = this._container,
				pagerContainer = this._pagerContainer,
				height = this.height,
				nonBodyHeight;

			container.height(height);

			if (height !== "auto") {
				height = container.height();

				nonBodyHeight = this._header.outerHeight(true);
				if (pagerContainer.parents(container).length) {
					nonBodyHeight += pagerContainer.outerHeight(true);
				}

				this._body.outerHeight(height - nonBodyHeight);
			}
		},

		showPrevPages: function () {
			let firstDisplayingPage = this._firstDisplayingPage,
				pageButtonCount = this.pageButtonCount;

			this._firstDisplayingPage = (firstDisplayingPage > pageButtonCount) ? firstDisplayingPage - pageButtonCount : 1;
			this._refreshPager();
		},

		showNextPages: function () {
			let firstDisplayingPage = this._firstDisplayingPage,
				pageButtonCount = this.pageButtonCount,
				pageCount = this._pagesCount();

			this._firstDisplayingPage = (firstDisplayingPage + 2 * pageButtonCount > pageCount)
				? pageCount - pageButtonCount + 1
				: firstDisplayingPage + pageButtonCount;

			this._refreshPager();
		},

		openPage: function (pageIndex) {
			if (pageIndex < 1 || pageIndex > this._pagesCount()) {
				return;
			}

			this._setPage(pageIndex);
			this._loadStrategy.openPage(pageIndex);
		},

		_setPage: function (pageIndex) {
			let
				firstDisplayingPage = this._firstDisplayingPage,
				pageButtonCount = this.pageButtonCount;

			this.pageIndex = pageIndex;
			if (pageIndex < firstDisplayingPage) {
				this._firstDisplayingPage = pageIndex;
			}
			if (pageIndex > firstDisplayingPage + pageButtonCount - 1) {
				this._firstDisplayingPage = pageIndex - pageButtonCount + 1;
			}
			this._callEventHandler(this.onPageChanged, {
				pageIndex: pageIndex
			});
		},

		_controllerCall: function (method, param, isCanceled, doneCallback) {
			if (isCanceled) {
				return $.Deferred().reject().promise();
			}
			let controller = this._controller;
			if (!controller || !controller[method]) {
				throw Error("controller has no method '" + method + "'");
			}
			return normalizePromise(controller[method](param)).done($.proxy(doneCallback, this)).fail($.proxy(this._errorHandler, this));
		},

		_errorHandler: function () {
			this._callEventHandler(this.onError, {
				args: $.makeArray(arguments)
			});
		},

		search: function (filter) {
			this._resetSorting();
			this._resetPager();
			return this.loadData(filter);
		},

		loadData: function (filter) {
			filter = filter || (this.filtering ? this.getFilter() : {});
			$.extend(filter, this._loadStrategy.loadParams(), this._sortingParams());
			let args = this._callEventHandler(this.onDataLoading, {
				filter: filter
			});
			return this._controllerCall("loadData", filter, args.cancel, function (loadedData) {
				if (!loadedData) {
					return;
				}
				this._loadStrategy.finishLoad(loadedData);
				this._callEventHandler(this.onDataLoaded, {
					data: loadedData
				});
			});
		},

		_sortingParams: function () {
			if (this.sorting && this._sortField) {
				return {
					sortField: this._sortField.name,
					sortOrder: this._sortOrder
				};
			}
			return {};
		},

		getSorting: function () {
			let sortingParams = this._sortingParams();
			return {
				field: sortingParams.sortField,
				order: sortingParams.sortOrder
			};
		},

	};

	$.fn.jTable = function (config) {
		let args = $.makeArray(arguments),
			methodArgs = args.slice(1),
			result = this;

		this.each(function () {
			let $element = $(this),
				instance = $element.data(TABLE_GRID),
				methodResult;

			if (instance) {
				if (typeof config === "string") {
					methodResult = instance[config].apply(instance, methodArgs);
					if (methodResult !== undefined && methodResult !== instance) {
						result = methodResult;
						return false;
					}
				} else {
					instance._detachWindowResizeCallback();
					instance._init(config);
					instance.render();
				}
			} else {
				new Grid($element, config);
			}
		});

		return result;
	};

	let fields = {};

	let setDefaults = function (config) {
		let componentPrototype;
		if ($.isPlainObject(config)) {
			componentPrototype = Grid.prototype;
		} else {
			componentPrototype = fields[config].prototype;
			config = arguments[1] || {};
		}
		$.extend(componentPrototype, config);
	};

	window.jTable = {
		fields     : fields,
		setDefaults: setDefaults,
		version    : '1.0.0'
	};

}(window, jQuery));

(function (jTable) {
	function PageLoadingStrategy(grid) {
		this._grid = grid;
		this._itemsCount = 0;
	}

	PageLoadingStrategy.prototype = {

		firstDisplayIndex: function () {
			return 0;
		},

		lastDisplayIndex: function () {
			return this._grid.option("data").length;
		},

		itemsCount: function () {
			return this._itemsCount;
		},

		openPage: function () {
			this._grid.loadData();
		},

		fromItem: function () {
			return this._fromItem;
		},
		toItem  : function () {
			return this._toItem;
		},

		loadParams: function () {
			let grid = this._grid;
			return {
				pageIndex: grid.option("pageIndex"),
				pageSize : grid.option("pageSize")
			};
		},

		reset: function () {
			return this._grid.loadData();
		},

		sort: function () {
			return this._grid.loadData();
		},

		finishLoad: function (loadedData) {
			this._itemsCount = loadedData.total;
			this._fromItem = loadedData.from;
			this._toItem = loadedData.to;
			this._grid.option("data", loadedData.data);
		}

	};

	jTable.loadStrategies = {
		PageLoadingStrategy: PageLoadingStrategy
	};

}(jTable, jQuery));

(function (jTable) {

	let isDefined = function (val) {
		return typeof (val) !== "undefined" && val !== null;
	};

	jTable.sortStrategies = {
		string: function (str1, str2) {
			if (!isDefined(str1) && !isDefined(str2)) {
				return 0;
			}

			if (!isDefined(str1)) {
				return -1;
			}

			if (!isDefined(str2)) {
				return 1;
			}

			return ("" + str1).localeCompare("" + str2);
		},

		number: function (n1, n2) {
			return n1 - n2;
		},

		date: function (dt1, dt2) {
			return dt1 - dt2;
		}

	};

}(jTable, jQuery));

(function (jTable, $, undefined) {

	function Field(config) {
		$.extend(true, this, config);
		this.sortingFunc = this._getSortingFunc();
	}

	Field.prototype = {
		name   : "",
		title  : null,
		css    : "",
		align  : "left",
		width  : 100,
		visible: true,
		sorting: true,
		sorter : "string",

		headerTemplate: function () {
			return (this.title === undefined || this.title === null) ? this.name : this.title;
		},

		itemTemplate: function (value) {
			return value;
		},

		_getSortingFunc: function () {
			let sorter = this.sorter;
			if ($.isFunction(sorter)) {
				return sorter;
			}
			if (typeof sorter === "string") {
				return jTable.sortStrategies[sorter];
			}
			throw Error("wrong sorter for the field \"" + this.name + "\"!");
		}
	};

	jTable.Field = Field;

}(jTable, jQuery));