<div class="input-block {{ $class ?? '' }}">
	<input type="text" id="{{ $datapickerId }}" value="{{ $selectedDay ?? '' }}" name="{{ $name ?? '' }}" data-old="{{ $old ?? ''}}" placeholder="{{ $placeholder ?? ''}}" class="default">
	<script>
		$(document).ready(function () {
			const $dataPicker = $("#{{ $datapickerId }}"),
				dataPickerParams = {
					dateFormat: "dd.mm.yy"
				};

			@if( isset($minDay))
				dataPickerParams["minDate"] = "{{ $minDay }}";
			@endif

					@if( isset($maxDay))
				dataPickerParams["maxDate"] = "{{ $maxDay }}";
			@endif

			$(function () {
				$dataPicker.datepicker(dataPickerParams);
			});

			$dataPicker.change(function () {
				console.log($dataPicker.val());
			});
		});
	</script>
</div>