@extends('pages._common')
@section('title')
	Картотека персоналу
@endsection

@section('content')
	<div class="panel js-grid-container">
		@include(
			'elements.search',
			[
				'id' => 'js-worker-search',
				'search' => $search,
				'filter' => $filters ?? []
			]
		)
		<div class="js-worker-pager pager vat d-inline"></div>
		<div id="js-worker-grid" class="js-table-grid"></div>
	</div>

	<script type="application/javascript">

		$(document).ready(function () {
			'use strict';

			const $workersGrid = $("#js-worker-grid");

			$workersGrid.jTable(
				{
					pagerContainer  : ".js-worker-pager",
					searchSelector  : "#js-worker-search",
					pageSize        : 15,
					fields          : [
						{name: "last_name", title: "Прізвище"},
						{name: "first_name", title: "Ім'я"},
						{name: "sub_name", title: "Побатькові"},
						{name: "structure_segments", title: "Відділ"},
						{name: "profession", title: "Посада"},
					],
					loadDataUrl     : "{{ route('paginator.workers') }}",
					rowClickUrl     : "{{ route('worker.details.redirect') }}",
					rowClickEntityId: "worker_id"
				}
			);
			$workersGrid.jTable('sort', {field: "{{ $sortField ?? 'last_name' }}", order: "{{ $sortOrder ?? 'asc' }}"});
		});

	</script>
@endsection

