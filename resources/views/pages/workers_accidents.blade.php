@extends('pages._common')
@section('title')
	Інциденти з персоналом
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
						{name: "full_name", title: "Працівник"},
						{name: "accident_name", title: "Вид інциденту"},
						{name: "accident_at", title: "Дата інциденту"},
					],
					loadDataUrl     : "{{ route('paginator.workers-accidents') }}",
					rowClickUrl     : "{{ route('accident.details.redirect') }}",
					rowClickEntityId: "accident_id"
				}
			);
			$accidentsGrid.jTable('sort', {field: "{{ $sortField ?? 'accident_at' }}", order: "{{ $sortOrder ?? 'asc' }}"});
		});

	</script>
@endsection

