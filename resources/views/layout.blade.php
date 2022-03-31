<!doctype html>
<html lang="ua-Ua">
<head>
	<meta charset="UTF-8">
	<title>Інформаційна система</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="{{ asset('css/main.css') }}">
	<link rel="stylesheet" href="{{ asset('css/common.css') }}">
	<link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
	<link rel="stylesheet" href="{{ asset('css/icons.css') }}">
	<link rel="stylesheet" href="{{ asset('css/grid/my-grid.css') }}">
	<link rel="stylesheet" href="{{ asset('css/grid/filters-bar.css') }}">
	<link rel="stylesheet" href="{{ asset('css/table-grid.css') }}">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="{{ asset('js/datapickerUaLocal.js') }}"></script>
	<script src="{{ asset('js/jquery.table-grid.js') }}"></script>
	<script src="{{ asset('js/menu.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>
@yield( 'body' )
</html>