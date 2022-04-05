@extends('pages._common')
@section('title')
Створення нового працівника
@endsection

@section('content')

<form id="main-form" method="POST" action="{{ route('worker.save.page') }}">
	{!! csrf_field() !!}
	@include('elements.ownworkerscard')

	<button id="createworkerbtn" class="btn primary-btn submit js-create-button" disabled>
		Зберегти
	</button>
</form>
<script type="application/javascript">
	$(document).ready(function () {
		'use strict';
		const
			$form = $(document).find("form"),
			$firstName = $form.find("#first-name"),
			$lastName = $form.find("#last-name"),
			$button = $form.find("button");

		function check(val) {
			if (val) {
				$button.removeAttr("disabled");
			} else {
				$button.attr("disabled", "disabled");
			}
		}

		$firstName.change(function (e) {
			check($(this).val());
		})
		$lastName.change(function (e) {
			check($(this).val());
		})
		$button.on("click", function () {
				$form.submit();
			}
		);

	});
</script>

@endsection