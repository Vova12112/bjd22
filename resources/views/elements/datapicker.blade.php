<input type="text" id="{{ $datapickerId }}" value="{{ $selectedDay ?? '' }}">
<script>
	$(document).ready(function ()	{
		const $dataPicker = $("#{{ $datapickerId }}"),
			dataPickerParams = {
				dateFormat: "dd.mm.yy"
			};

		@if( isset($minDay))
			dataPickerParams["minDate"] = "{{ $minDay }}";
		@endif


		$(function () {
			$dataPicker.datepicker(dataPickerParams);
		});

		$dataPicker.change(function () {
			console.log($dataPicker.val());
		});
	});
</script>

<script>
	// $dataPicker.datepicker({
	// 	dateFormat: "yy-mm-dd",
	// 	maxDate   : '+1m +10d',
	// 	minDate   : -10
	// });

</script>
{{--{{ $test ?? 'test empty' }}--}}