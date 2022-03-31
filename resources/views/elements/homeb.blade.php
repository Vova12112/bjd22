<div class="containerCards">
	<h1 class="card-header">
		{{ $title }}
	</h1>
	<hr class="card-line">
	<p class="card-description">
		{!! $description !!}
	</p>
	<button class="btn card-btn {{ $btnClass ?? '' }}" {!! $btnAttr ?? '' !!}>{{ $textbtn }}</button>
</div>