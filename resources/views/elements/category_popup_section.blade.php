@extends('elements.popup_section')

@section('section-content')
	<style type="text/css">
		.popup-section {
			position: relative;
		}
		.popup-section .cancel-row {
			position: absolute;
			top:      10px;
			right:    10px;
		}
		.popup-section .cancel-row .js-cancel-section {
			cursor:    pointer;
			font-size: 14px;
			color:     #2374a1;
		}
		.popup-section .cancel-row .js-cancel-section:hover {
			color: #3B3C5A;
		}
		.popup-section .action-row {
			width:           100%;
			display:         flex;
			padding-top:     35px;
			justify-content: space-between;
		}
	</style>
	<div class="cancel-row">
		<span class="js-cancel-section">Закрити</span>
	</div>
	@include(
	'elements.input',
	[
		'inputId' => 'category-details-name',
		'label' => 'Назва категорії',
		'value' => isset($category) ? $category->getName() : '',
		'old' => isset($category) ? $category->getName() : '',
		'name' => 'name',
		'classes' => '',
	]
)
	<div class="action-row">
		<button class="delete-category js-delete-category btn danger">Видалити</button>
		<button class="save-category js-save-category btn" disabled>Зберегти</button>
	</div>

	<script type="text/javascript">
		$(document).ready(function () {
			const $section = $(".popup-section"),
				$inputs = $section.find("input.default"),
				$saveBtn = $section.find(".action-row .js-save-category"),
				$deleteBtn = $section.find(".action-row .js-delete-category"),
				$closeBtn = $section.find(".cancel-row .js-cancel-section");

			$closeBtn.on("click", function () {
				$section.remove();
			});

			$inputs.on("keyup", function () {
				let $isChanged = false;
				$inputs.each(function () {
					if ($(this).val() !== $(this).data("old")) {
						$isChanged = true;
						return false;
					}
				})
				if ($isChanged) {
					$saveBtn.removeAttr("disabled")
				} else {
					$saveBtn.prop("disabled", "true");
				}
			});

			$saveBtn.on("click", function () {
				const name = $section.find("input.default").val();
				ajaxRequest(
					"{{ route('category.save', ['id' => isset($category) ? $category->getId() : -1]) }}",
					"POST",
					"json",
					{
						'name': name,
					}
				);
			});

			$deleteBtn.on("click", function () {
				ajaxRequest(
					"{{ route('category.delete') }}",
					"POST",
					"json",
					{
						"id": "{{ isset($category) ? $category->getId() : '-1' }}",
					}
				);
			});
		});
	</script>
@endsection