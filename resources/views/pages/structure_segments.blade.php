@extends('pages._common')
@section('title')
	Відділи підприємства
@endsection

@section('content')
	<div class="panel js-grid-container">
		@include(
			'elements.search',
			[
				'id' => 'js-segment-search',
				'search' => $search,
				'filter' => $filters ?? []
			]
		)
		<div class="js-segment-pager pager vat d-inline"></div>
		<div id="js-segment-grid" class="js-table-grid"></div>
	</div>

	<script type="application/javascript">

		$(document).ready(function () {
			'use strict';

			const $segmentsGrid = $("#js-segment-grid");

			$segmentsGrid.jTable(
				{
					pagerContainer  : ".js-segment-pager",
					searchSelector  : "#js-segment-search",
					pageSize        : 15,
					fields          : [
						{name: "name", title: "Назва відділу"},
					],
					loadDataUrl     : "{{ route('paginator.segments') }}",
					rowClickUrl     : "{{ route('segment.details.redirect') }}",
					rowClickEntityId: "segment_id"
				}
			);
			$segmentsGrid.jTable('sort', {field: "{{ $sortField ?? 'last_name' }}", order: "{{ $sortOrder ?? 'asc' }}"});
		});

	</script>
@endsection