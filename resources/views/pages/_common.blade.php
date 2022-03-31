@extends('layout')

@section('body')
	<body>
		<div id="wrapper">
			@csrf
			<div id="loader" style="display: none;"><img src="{{ asset('img/load.gif') }}" alt="loader"></div>
			@include('elements.popup')
			<div id="action-column">
				<div class="main-title nav-link" style="cursor: pointer;" data-route="{{ route('home') }}">
					Інформаціна<br>Система
				</div>
				@if ( isset($buttons) && count($buttons) > 0)
					<div class="action d-panel menu buttons">
						@foreach( $buttons as $button )
							<button title="{{ $button['alt'] ?? '' }}" class="menu-buttons {{ $button['class'] ?? '' }}" {!! $button['args'] ?? '' !!}>{{ $button['label'] ?? '' }}</button>
						@endforeach
					</div>
				@endif
				<div class="action d-panel menu nav">
					<div class="nav-group">
						<div class="nav category">
							<span>Працівники</span>
							<i class="icon arrow"></i>
						</div>
						<div class="nav subcategory nav-link" data-route="{{ route('workers') }}">
							Всі працівники
						</div>
						<div class="nav subcategory nav-link" data-route="">
							Посади
						</div>
					</div>
					<div class="nav-group">
						<div class="nav category">
							<span> Підприємство </span>
							<i class="icon arrow"></i>
						</div>
						<div class="nav subcategory nav-link" data-route="{{ route('organization') }}">
							Деталі
						</div>
						<div class="nav subcategory nav-link" data-route="">
							Відділи
						</div>
					</div>
					<div class="nav-group">
						<div class="nav category">
							<span> Травматизм </span>
							<i class="icon arrow"></i>
						</div>
						<div class="nav subcategory nav-link" data-route="">
							Лог інцидентів
						</div>
						<div class="nav subcategory nav-link" data-route="">
							Види інцидентів
						</div>
					</div>
				</div>
				<script type="text/javascript">
					$(document).ready(function () {
						const $navLinks = $("#wrapper .nav-link");

						$navLinks.on("click", function () {
							window.location.href = $(this).data("route");
						});
					});
				</script>
			</div>
			<div id="content-column">
				<div class="content d-panel">
					<div class="content-header">
						@yield('title')
					</div>
					<div class="content-wrapper">
						@yield('content')
					</div>
				</div>
			</div>
		</div>
		@include('_common.console_scripts')
		@include('_common.scripts')
	</body>
@endsection