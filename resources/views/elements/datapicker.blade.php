<input type="text" id="{{ $datapickerId }}">

<script>
	$(function () {
		$("#{{ $datapickerId }}").datepicker();
	});
</script>


{{ $test ?? 'test empty' }}