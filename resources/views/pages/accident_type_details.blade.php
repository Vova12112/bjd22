<?php
/*** @var AccidentType $accidentType */

use App\Models\AccidentType;
use App\Models\Controllers\AccidentsModelController;
use App\Models\Repo\AccidentsModelRepo;

$accidentController = new AccidentsModelController(new AccidentsModelRepo());
?>

@extends('pages._common')
@section('title')
	{{ isset($accidentType) ?  $accidentType->getName()  : 'Новий тип інциденту' }}
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
	<form action="{{ route('accident_type.save', ['id' => isset($accidentType) ? $accidentType->getId() : -1]) }}" method="POST">
		@csrf
		@include(
			'elements.input',
			[
				'inputId' => 'structure_segments_details',
				'label' => 'Тип інциденту',
				'name'=>'name',
				'value' => isset($accidentType) ? $accidentType->getName() : '',
				'old' => isset($accidentType) ? $accidentType->getName() : '',
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