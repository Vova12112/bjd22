<?php

use App\Models\Controllers\ProfessionsModelController;
use App\Models\Profession;
use App\Models\Repo\ProfessionsModelRepo;

/*** @var Profession $profession */
$professionController = new ProfessionsModelController(new ProfessionsModelRepo())
?>

@extends('pages._common')
@section('title')
	{{ isset($profession) ? 'Посада "' . $profession->getName() . '"' : 'Нова посада (професія)' }}
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
	<form action="{{ route('profession.save', ['id' => isset($profession) ? $profession->getId() : -1]) }}" method="POST">
		@csrf
		@include(
			'elements.input',
			[
				'inputId' => 'profession-details-name',
				'label' => 'Назва посади (профессії)',
				'name'=>'name',
				'value' => isset($profession) ? $profession->getName() : '',
				'old' => isset($profession) ? $profession->getName() : '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE
			]
		)
		@include(
			'elements.select',
			[
			  'id' => 'profession-details-category',
			  'label' => 'Категорія посади (професії)',
			  'selected' => isset($profession) ? $profession->getProfessionCategoryId() : '',
			  'old'=> isset($profession) ? $profession->getProfessionCategoryId() : '',
			  'labelClass' => '',
			  'isWithChoose' => TRUE,
			  'name' => 'category_id',
			  'options' => $professionController->getCategoriesList(),
			]
		)

		<div class="action-row">
			<button class="btn js-save-category" disabled>Зберегти</button>
		</div>
	</form>

	<script type="text/javascript">
		$(document).ready(function () {
			const
				$page = $(".content-wrapper"),
				$nameInput = $page.find("input[name=name]"),
				$categorySelect = $page.find("select[name=category_id]"),
				$saveBtn = $page.find(".js-save-category")
			;

			function isFormChanged() {
				return $nameInput.val() !== '' && $categorySelect.val() !== '-' && ($nameInput.val() !== $nameInput.data('old') || $categorySelect.val().toString() !== $categorySelect.data('old').toString());
			}

			$nameInput.on("keyup", function (){
				if (isFormChanged()){
					$saveBtn.removeAttr("disabled");
				} else {
					$saveBtn.prop("disabled", "true");
				}
			});

			$categorySelect.on("change", function (){
				if (isFormChanged()){
					$saveBtn.removeAttr("disabled");
				} else {
					$saveBtn.prop("disabled", "true");
				}
			});
		});
	</script>

@endsection