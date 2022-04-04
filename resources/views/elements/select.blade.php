<div class="p-rel tal">
	<label class="{{ $labelClass ?? '' }}" for="{{ $id }}">{!! $label ?? '' !!}</label>
	<select id="{{ $id }}" class="{{ $class ?? '' }}" name="{{ $name }}" data-old="{{ $old }}" {{ $additionalParameter ?? '' }}>
		@if( $isWithChoose ?? FALSE )
			<option value="-"> Оберіть варіант </option>
		@endif
		@foreach ( $options as $optionName => $optionValue )
			<option value="{{ $optionName }}" {{ ( ! empty($selected) && (string) $optionName === (string) $selected) ? 'selected' : '' }}>
				{!! $optionValue !!}
			</option>
		@endforeach
	</select>
</div>

<script type="application/javascript">
	$(document).ready(function () {
		"use strict";
		$("#{{ $id }}").select2({minimumResultsForSearch: 10, width: "100%"});
	});
</script>