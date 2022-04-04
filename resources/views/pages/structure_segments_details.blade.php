<?php
/*** @var StructureSegment $segment */

use App\Models\Controllers\SegmentsModelController;
use App\Models\Repo\SegmentsModelRepo;
use App\Models\StructureSegment;

$segmentController = new SegmentsModelController(new SegmentsModelRepo())
?>

@extends('pages._common')
@section('title')
	{{ isset($segment) ? 'Відділ "' . $segment->getName() . '"' : 'Новий відділ' }}
@endsection

@section('content')
	<style type="text/css">
		.content-wrapper .input-block {
			margin: 25px 0;
		}
		.content-wrapper .action-row {
			margin:     35px 0;
			text-align: center;
		}
	</style>
	<form action="{{ route('segment.save', ['id' => isset($segment) ? $segment->getId() : -1]) }}" method="POST">
		@csrf
		@include(
			'elements.input',
			[
				'inputId' => 'structure_segments_details',
				'label' => 'Назва відділу',
				'name'=>'name',
				'value' => isset($segment) ? $segment->getName() : '',
				'old' => isset($segment) ? $segment->getName() : '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
			]
		)

		<div class="action-row">
			<button class="btn js-save-segment" disabled>Зберегти</button>
		</div>
	</form>

	<script type="text/javascript">
		$(document).ready(function () {
			const
				$page = $(".content-wrapper"),
				$nameInput = $page.find("input[name=name]"),
				$saveBtn = $page.find(".js-save-segment")
			;

			function isFormChanged() {
				return $nameInput.val() !== '' && $nameInput.val() !== $nameInput.data('old');
			}

			$nameInput.on("keyup", function () {
				if (isFormChanged()) {
					$saveBtn.removeAttr("disabled");
				} else {
					$saveBtn.prop("disabled", "true");
				}
			});

		});
	</script>
@endsection