@extends('pages._common')
@section('title')
	Інциденти з персоналом
@endsection

@section('content')
	<style type="text/css">
		.content-wrapper .js-grid-container {
			position: relative;
		}
		.content-wrapper .js-grid-container .js-filters {
			position: absolute;
			z-index:  500;
			top:      23px;
			left:     280px;
		}
	</style>
	<div class="panel js-grid-container">
		@include(
			'elements.search',
			[
				'id' => 'js-accident-search',
				'search' => $search,
				'filter' => $filters ?? []
			]
		)
		<button class="btn js-filters js-popup-noscript" data-popup="workers-accidents-filters-popup" data-popup-class="js-workers-accidents-filters-popup">Фільтри</button>
		<div class="js-accident-pager pager vat d-inline"></div>
		<div id="js-accident-grid" class="js-table-grid"></div>
		<noscript class="workers-accidents-filters-popup hidden" data-title="Фільтри">
			<style type="text/css">
				#main-popup .popup-wrapper {
					width: 350px;
				}
				#main-popup .popup-wrapper .popup-content {
					padding:    10px;
					text-align: center;
				}
				#main-popup .popup-wrapper .popup-content h3 {
					margin: 5px;
				}
				#main-popup .popup-wrapper .popup-content .period-datepicker {
					width:   45%;
					display: inline-block;
				}
				#main-popup .popup-wrapper .popup-content .filter-input {
					width:  90%;
					margin: 5px 5%;
				}
				#main-popup .popup-wrapper .popup-content .action-row {
					width:  100%;
					margin: 15px 0;
				}
			</style>
			<div id="js-workers-accidents-filters">
				<h3>Період</h3>
				@include(
					'elements.datapicker',
					[
						'datapickerId' => 'workers-accidents-from',
						'placeholder' => 'Від',
						'class' => 'period-datepicker',
						'selectedDay' => '',
						'minDay' => '',
						'maxDay' => now('UTC')->format('d.m.Y'),
						'name' => 'from'
					]
				)
				@include(
					'elements.datapicker',
					[
						'datapickerId' => 'workers-accidents-to',
						'placeholder' => 'До',
						'class' => 'period-datepicker',
						'selectedDay' => '',
						'minDay' => '',
						'maxDay' => now('UTC')->format('d.m.Y'),
						'name' => 'to'
					]
				)
				@include(
					'elements.select',
					[
						'id' => 'workers-accidents-type',
						'classes' => 'filter-input',
						'label' => 'Тип інциденту',
						'value' => '',
						'labelClass' => '',
						'isWithChoose' => TRUE,
						'name' => 'accidents_type_id',
						'options' => \App\ValuesObject\AccidentType::getAccidentTypes(),
					]
				)
				<br>
				<h3>Працівник</h3>
				@include(
					'elements.input',
					[
						'inputId' => 'workers-accidents-last-name',
						'classes' => 'filter-input',
						'label' => 'Прізвище',
						'value' => '',
						'isReadOnly' => FALSE,
						'isDisable' => FALSE,
						'name' => 'last_name'
					]
				)
				@include(
					'elements.input',
					[
						'inputId' => 'workers-accidents-first-name',
						'classes' => 'filter-input',
						'label' => "Ім'я",
						'value' => '',
						'isReadOnly' => FALSE,
						'isDisable' => FALSE,
						'name' => 'first_name'
					]
				)
				@include(
					'elements.input',
					[
						'inputId' => 'workers-accidents-sub-name',
						'classes' => 'filter-input',
						'label' => "Побатькові",
						'value' => '',
						'isReadOnly' => FALSE,
						'isDisable' => FALSE,
						'name' => 'sub_name'
					]
				)
				@include(
					'elements.select',
					[
						'id' => 'workers-accidents-sex',
						'classes' => 'filter-input',
						'label' => 'Стать',
						'value' => '',
						'labelClass' => '',
						'isWithChoose' => TRUE,
						'name' => 'sex',
						'options' => \App\ValuesObject\Genders::getSex(),
					]
				)
				@include(
					'elements.select',
					[
						'id' => 'workers-accidents-profession',
						'classes' => 'filter-input',
						'label' => 'Посада (професія)',
						'value' => '',
						'labelClass' => '',
						'isWithChoose' => TRUE,
						'name' => 'profession_id',
						'options' => \App\ValuesObject\Professions::getCategories(),
					]
				)
				@include(
					'elements.select',
					[
						'id' => 'workers-accidents-category',
						'classes' => 'filter-input',
						'label' => 'Категорія посади',
						'value' => '',
						'labelClass' => '',
						'isWithChoose' => TRUE,
						'name' => 'category_id',
						'options' => \App\ValuesObject\Categories::getCategories(),
					]
				)
				@include(
					'elements.select',
					[
						'id' => 'workers-accidents-segment',
						'classes' => 'filter-input',
						'label' => 'Відділ',
						'value' => '',
						'labelClass' => '',
						'isWithChoose' => TRUE,
						'name' => 'segment_id',
						'options' => \App\ValuesObject\Division::getDivisions(),
					]
				)
				<div class="action-row">
					<button class="btn danger js-clear-filters">Скинути все</button>
					<button class="btn run-filters js-set-filters">Застосувати фільтри</button>
				</div>
				<script type="text/javascript">
					$(document).ready(function () {
						'use strict';

						const
							$contentWrapper = $("#wrapper .content-wrapper"),
							$tableGrid = $("#js-accident-grid"),
							$filtersBar = $("#js-workers-accidents-filters"),
							$inputs = $filtersBar.find("input"),
							$selects = $filtersBar.find("select"),
							$clearFiltersBtn = $filtersBar.find(".js-clear-filters"),
							$setFiltersBtn = $filtersBar.find(".js-set-filters")
						;

						function initFiltersBar() {
							let filters = $contentWrapper.data("filters");
							if (filters === undefined) {
								return true;
							}
							for (const filter in filters) {
								$inputs.each(function () {
									if (filter === $(this).prop("name")) {
										$(this).val(filters[filter]);
									}
								});
								$selects.each(function () {
									if (filter === $(this).prop("name")) {
										$(this).val(filters[filter]);
										$(this).trigger("change");
									}
								});
							}
						}

						function setFilters() {
							const filters = {};
							$inputs.each(function () {
								filters[$(this).prop("name")] = $(this).val();
							});
							$selects.each(function () {
								filters[$(this).prop("name")] = $(this).val();
							});
							for (const filter in filters) {
								if (filters[filter] === '' || filters[filter] === null || filters[filter] === '-'){
									delete filters[filter];
								}
							}
							$contentWrapper.data("filters", filters);
							popup.hideAll();
							$tableGrid.jTable("loadData");
						}

						function clearFilters() {
							$contentWrapper.data("filters", {});
							popup.hideAll();
							$tableGrid.jTable("loadData");
						}

						$setFiltersBtn.on("click", function () {
							setFilters()
						});

						$clearFiltersBtn.on("click", function () {
							clearFilters()
						});

						initFiltersBar();

					});
				</script>
			</div>
		</noscript>
	</div>

	<script type="application/javascript">

		$(document).ready(function () {
			'use strict';

			const $accidentsGrid = $("#js-accident-grid");

			noScriptPopupHandlers();

			$accidentsGrid.jTable(
				{
					pagerContainer  : ".js-accident-pager",
					searchSelector  : "#js-accident-search",
					pageSize        : 15,
					fields          : [
						{name: "full_name", title: "Працівник"},
						{name: "accident_name", title: "Вид інциденту"},
						{name: "accident_at", title: "Дата інциденту"},
					],
					filters         : "selector",
					filtersSelector : $(".content-wrapper"),
					loadDataUrl     : "{{ route('paginator.workers-accidents') }}",
					rowClickUrl     : "{{ route('accident.details.redirect') }}",
					rowClickEntityId: "accident_id"
				}
			);
			$accidentsGrid.jTable('sort', {field: "{{ $sortField ?? 'accident_at' }}", order: "{{ $sortOrder ?? 'asc' }}"});
		});

	</script>
@endsection

