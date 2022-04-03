@extends('pages._common')
@section('title')
	Види інцедентів
@endsection

@section('content')
	<div class="panel js-grid-container">
		@include(
			'elements.search',
			[
				'id' => 'js-accident-search',
				'search' => $search,
				'filter' => $filters ?? []
			]
		)
		<div class="js-accident-pager pager vat d-inline"></div>
		<div id="js-accident-grid" class="js-table-grid"></div>
	</div>

	<script type="application/javascript">

		$(document).ready(function () {
			'use strict';

			const $accidentsGrid = $("#js-accident-grid");

			$accidentsGrid.jTable(
				{
					pagerContainer  : ".js-accident-pager",
					searchSelector  : "#js-accident-search",
					pageSize        : 15,
					fields          : [
						{name: "name", title: "Найменування"},
						{name: "created_at", title: "Запис створено"},
						{name: "updated_at", title: "Редагували"},
					],
					loadDataUrl     : "{{ route('paginator.accidents') }}",
					rowClickUrl     : "",
					rowClickEntityId: "id"
				}
			);
			$accidentsGrid.jTable('sort', {field: "{{ $sortField ?? 'name' }}", order: "{{ $sortOrder ?? 'asc' }}"});
		});

	</script>
@endsection
