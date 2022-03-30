<div class="js-search-container p-rel vat d-inline {{ $containerClass ?? '' }}">
	<label for="{{ $id }}"></label>
	<input type="text" class="pl-30 pr-40" id="{{ $id }}" name="search" value="{{ $search }}">
	@if( ! empty($filters['elements']) )
		<i class="js-filters-btn icon filter @if( $filters['is_active']) active @endif p-absolute r-0 mt-20 c-pointer"></i>
		<i class="js-clear-filter-btn icon close-2 active d-inline @if( ! $filters['is_active']) hidden @endif p-absolute ml-10 mt-20 c-pointer"></i>
		<div class="js-filters-section filters p-absolute w-100 p-20 mt--10 hidden">
			@foreach( $filters['elements'] as $filter )
				@include(
					'console.elements.select',
					[
						'id' => $filter['id'],
						'label' => $filter['label'],
						'labelClass' => '',
						'isWithChoose' => TRUE,
						'name' => $filter['name'],
						'selected' => $filter['selected'],
						'options' => $filter['options'],
					]
				)
			@endforeach
			<button class="btn outline-btn js-clear-filter-btn mt-20 @if( ! $filters['is_active']) hidden @endif">{{ trans('general.clear') }}</button>
			<button class="btn primary-btn js-submit fr mt-20">{{ trans('general.submit') }}</button>
		</div>
	@endif
</div>

<script type="application/javascript">
	$(document).ready(function () {
		'use strict';

		let
			$search = $("#{{ $id }}"),
			$container = $search.closest(".js-grid-container"),
			$tableGrid = $container.find(".js-table-grid"),
			$searchButton = $container.find(".js-search-btn"),
			$clearSearchButton = $container.find(".js-clear-search-btn");

		function loadData() {
			$tableGrid.jTable("loadData");
		}

		function checkSearch() {
			let search = $search.val();
			if (search === "") {
				$clearSearchButton.addClass("hidden");
			} else {
				$clearSearchButton.removeClass("hidden");
			}
		}

		$searchButton.unbind("click").on("click", function (event) {
			event.preventDefault();
			loadData();
		});

		$search.unbind("keyup").on("keyup", function (event) {
			event.preventDefault();
			checkSearch();
			if (event.which === 13) {
				loadData();
			}
		});

		$clearSearchButton.unbind("click").on("click", function (event) {
			event.preventDefault();
			$search.val("");
			checkSearch();
			loadData();
		});

		checkSearch();

		@if( ! empty($filters['elements']) )

		let
			$filterSection = $container.find(".js-filters-section"),
			$filtersButton = $container.find(".js-filters-btn"),
			$clearFiltersButton = $container.find(".js-clear-filter-btn"),
			$submitButton = $container.find(".js-submit");

		function resetFilterData() {
			$filterSection.find('select').val('-').trigger('change');
			$filtersButton.removeClass("active");
			showSection($clearFiltersButton, false);
			showSection($filterSection, false);
		}

		function isFilterWithData() {
			let
				isWithData = false,
				filters = getElementInputs($filterSection);
			$.each(filters, function (key, value) {
				if (value !== "" && value !== "-" && value !== null && value !== undefined) {
					isWithData = true;
				}
			});
			return isWithData;
		}

		$filtersButton.unbind("click").on("click", function (event) {
			event.preventDefault();
			showSection($filterSection, $filterSection.hasClass("hidden"));
		});

		$clearFiltersButton.unbind("click").on("click", function (event) {
			event.preventDefault();
			resetFilterData();
			loadData();
		});

		$submitButton.unbind("click").on("click", function (event) {
			event.preventDefault();
			if (isFilterWithData()) {
				$filtersButton.addClass("active");
				$clearFiltersButton.removeClass("hidden");
			} else {
				resetFilterData();
			}
			showSection($filterSection, false);
			loadData();
		});

		@endif

	});
</script>