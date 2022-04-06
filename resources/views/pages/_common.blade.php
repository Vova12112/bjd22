@extends('layout')

@section('body')
	<body>
		<div class="notification">
			<div class="notification-body">
				<div class="notification-close">X</div>
				Тест
			</div>
		</div>
		@include('elements.popup')
		<div id="wrapper">
			@csrf
			<div id="loader" style="display: none;"><img src="{{ asset('img/load.gif') }}" alt="loader"></div>
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
						<div class="nav subcategory nav-link" data-route="{{ route('professions') }}">
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
						<div class="nav subcategory nav-link" data-route="{{ route('organization.segments') }}">
							Відділи
						</div>
					</div>
					<div class="nav-group">
						<div class="nav category">
							<span> Травматизм </span>
							<i class="icon arrow"></i>
						</div>
						<div class="nav subcategory nav-link" data-route="{{ route('accidents.workers') }}">
							Лог інцидентів
						</div>
						<div class="nav subcategory nav-link" data-route="{{ route('accidents.show') }}">
							Види інцидентів
						</div>
					</div>
				</div>
				<script type="text/javascript">
					function showNotif (text, cl){
						$(".notification .notification-body").addClass("active");
						$(".notification .notification-body").addClass(cl);
						$(".notification .notification-body").html(text);
						setTimeout(() => {$(".notification .notification-body").removeClass("active")}, 3000);
						setTimeout(() => {$(".notification .notification-body").removeClass(cl)}, 5000);
					}
					$(document).ready(function () {
						const $navLinks = $("#wrapper .nav-link");

						$navLinks.on("click", function () {
							if ($(this).data('method') === 'POST') {
								ajaxRequest(
									$(this).data("route"),
									"POST",
									"json",
									{
										"_token": $("input[name=_token]").first().val()
									},
									function () {
									}
								)
							} else {
								window.location.href = $(this).data("route");
							}
						});

						$(".notification .notification-body.active .notification-close").on("click", function (){
							$(".notification .notification-body").removeClass("active");
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