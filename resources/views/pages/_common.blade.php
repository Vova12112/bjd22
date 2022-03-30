@extends('layout')

@section('body')
	<body>
		<div id="wrapper">
			@csrf
			<div id="loader" style="display: none;"><img src="{{ asset('img/load.gif') }}" alt="loader"></div>
			@include('elements.popup')
			<div id="action-column">
				<div class="main-title">
					Інформаціна<br>Система
				</div>
				<div class="action d-panel menu buttons">

				</div>
				<div class="action d-panel menu nav">
					<div class="nav-group">
						<div class="nav category">
							<span>Працівники</span>
							<i class="icon arrow"></i>
						</div>
						<div class="nav subcategory">
							Всі працівники
						</div>
						<div class="nav subcategory">
							Посади
						</div>
					</div>
					<div class="nav-group">
						<div class="nav category">
							<span> Підприємство </span>
							<i class="icon arrow"></i>
						</div>
						<div class="nav subcategory">
							Відділи
						</div>
					</div>
					<div class="nav-group">
						<div class="nav category">
							<span> Травматизм </span>
							<i class="icon arrow"></i>
						</div>
						<div class="nav subcategory">
							Лог інцидентів
						</div>
						<div class="nav subcategory">
							Види інцидентів
						</div>
					</div>
				</div>
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