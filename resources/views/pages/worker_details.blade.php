<?php
/*** @var \App\Models\Worker $worker */
?>

@extends('pages._common')
@section('title')
	{{ $worker->getFullName() }}
@endsection

@section('content')
	<form id="main-form" method="POST" action="{{ route('worker.save.page') }}">
		@csrf
		@include(
		'elements.ownworkerscard'
		)
		<button id="createworkerbtn" class="btn primary-btn submit js-create-button">
			Зберегти
		</button>
	</form>
	<script type="application/javascript">
		$(document).ready(function () {
			'use strict';
			const
				$form = $(document).find("#main-form"),
				$deleteBtn = $(".js-delete-worker"),
				$button = $form.find("button");

			$deleteBtn.on("click", function () {
				$form.prop("action", "{{ route('worker.delete.page') }}");
				$form.submit();
			});

			$button.on("click", function () {
				$form.submit();
			});
		});
	</script>
@endsection
