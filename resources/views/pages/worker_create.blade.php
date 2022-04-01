@extends('pages._common')
@section('title')
	Створення нового працівника
@endsection

@section('content')

	<form id="main-form" method="POST" action="{{ route('worker.save.page') }}">
		{!! csrf_field() !!}
		@include('elements.ownworkerscard')

		<button id="createworkerbtn" class="btn primary-btn submit js-create-button">
			Зберегти
		</button>
	</form>
	<script type="application/javascript">
		$(document).ready(function () {
			'use strict';
			const
				$form = $(document).find("form"),
				$button = $form.find("button");

			$button.on("click", function () {
				$form.submit();
			});
		});
	</script>

@endsection