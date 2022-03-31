@extends('pages._common')
@section('title')
	Професії (посади)
@endsection

@section('content')
	<div class="panel js-grid-container">
		@include(
			'elements.search',
			[
				'id' => 'js-profession-search',
				'search' => $search,
				'filter' => $filters ?? []
			]
		)
		<div class="js-profession-pager pager vat d-inline"></div>
		<div id="js-profession-grid" class="js-table-grid"></div>

		<noscript class="categories-popup hidden" data-title="Категорії посад (професій)">
			<style type="text/css">
				#main-popup .categories-wrapper {
					position:        relative;
					width:           100%;
					box-sizing:      border-box;
					padding:         15px;
					display:         flex;
					justify-content: space-between;
				}
				#main-popup .categories-wrapper .new-category {
					position: absolute;
					z-index:  1000;
					top:      20px;
					left:     35px;
				}
				#main-popup .categories-wrapper .new-category .js-add-new-category {
					cursor:    pointer;
					font-size: 14px;
					color:     #2374a1;
				}
				#main-popup .categories-wrapper .new-category .js-add-new-category:hover {
					color: #3B3C5A;
				}
				#main-popup .categories-wrapper .categories-table {
					position:   relative;
					display:    inline-block;
					width:      100%;
					box-sizing: border-box;
					padding:    15px;
					text-align: left;
				}
				#main-popup .categories-wrapper .action-panel {
					display:   inline-block;
					padding:   15px;
					max-width: 35%;
					min-width: 35%;
					height:    100%;
				}
				#main-popup .categories-wrapper .action-panel .popup-section-title {
					user-select:   none;
					font-family:   'Montserrat-Regular', serif;
					font-style:    normal;
					font-weight:   500;
					font-size:     24px;
					line-height:   39px;
					text-align:    left;
					color:         #3B3C5A;
					margin-bottom: 20px;
					padding-top:   20px;
				}
				#main-popup .categories-wrapper.active .categories-table {
					display:    inline-block;
					width:      100%;
					max-width:  60%;
					box-sizing: border-box;
					padding:    15px;
				}
				#main-popup .categories-wrapper.active .categories-table:after {
					content:          " ";
					position:         absolute;
					top:              15%;
					right:            0;
					width:            1px;
					height:           70%;
					background-color: #BED4E8;
				}
			</style>
			<div class="categories-wrapper active">
				<div class="new-category">
					<span class="js-add-new-category">+ Нова категорія</span>
				</div>
				<div class="categories-table js-grid-container">
					@include(
						'elements.search',
						[
							'id' => 'js-categories-search',
							'search' => $search,
							'filter' => $filters ?? []
						]
					)
					<div class="js-categories-pager pager vat d-inline"></div>
					<div id="js-categories-grid" class="js-table-grid"></div>

					<script type="application/javascript">
						$(document).ready(function () {
							'use strict';

							const $categoriesGrid = $("#js-categories-grid"),
								$actionPanel = popup.getMainPopup().find(".action-panel"),
								$newCategoryBtn = popup.getMainPopup().find(".categories-wrapper .new-category .js-add-new-category");

							$newCategoryBtn.on("click", function (){
								ajaxRequest(
									"{{ route('category.details') }}",
									"POST",
									"json",
									{},
									function (response) {
										$actionPanel.html("");
										$actionPanel.html(response.html)
									}
								)
							});

							$categoriesGrid.jTable(
								{
									pagerContainer  : ".js-categories-pager",
									searchSelector  : "#js-categories-search",
									pageSize        : 10,
									fields          : [
										{name: "name", title: "Назва посади (професії)"},
									],
									loadDataUrl     : "{{ route('paginator.categories') }}",
									rowClickUrl     : "function",
									rowClickFunction: function (args) {
										ajaxRequest(
											"{{ route('category.details') }}",
											"POST",
											"json",
											{
												"id": args.item.id,
											},
											function (response) {
												$actionPanel.html("");
												$actionPanel.html(response.html)
											}
										)
									}
								}
							);
							$categoriesGrid.jTable('sort', {field: "{{ $sortField ?? 'name' }}", order: "{{ $sortOrder ?? 'asc' }}"});
						});

					</script>
				</div>
				<div class="action-panel">

				</div>
			</div>
		</noscript>
	</div>

	<script type="application/javascript">

		$(document).ready(function () {
			'use strict';

			const
				$professionsGrid = $("#js-profession-grid"),
				$professionCategoriesBtn = $("#wrapper .js-categories")
			;

			$professionCategoriesBtn.on("click", function () {
				noScriptPopupHandlers();
			});

			$professionsGrid.jTable(
				{
					pagerContainer  : ".js-profession-pager",
					searchSelector  : "#js-profession-search",
					pageSize        : 15,
					fields          : [
						{name: "name", title: "Назва посади (професії)"},
					],
					loadDataUrl     : "{{ route('paginator.professions') }}",
					rowClickUrl     : "{{ route('profession.details.redirect') }}",
					rowClickEntityId: "profession_id"
				}
			);
			$professionsGrid.jTable('sort', {field: "{{ $sortField ?? 'name' }}", order: "{{ $sortOrder ?? 'asc' }}"});
		});

	</script>
@endsection