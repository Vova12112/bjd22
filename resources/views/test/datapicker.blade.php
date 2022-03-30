@extends('test._common')

@section('title')
	Datapicker
@endsection

@section('content')
	<style type="text/css">
		.wrapper {
			width:            400px;
			height:           250px;
			position:         absolute;
			top:              50%;
			left:             50%;
			margin-top:       -250px;
			margin-left:      -125px;
			border-radius:    10px;
			background-color: rgba(95, 158, 160, 0.15);
			text-align:       center;
			padding:          25px 30px;
		}
	</style>

	<div class="wrapper">
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker',
				'selectedDay' => '12.02.2022',
				'minDay' => '-1',
				'maxDay' => '30',
			]
		)
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker1',
				'selectedDay' => \Carbon\Carbon::now('UTC')->format('d.m.Y'),
				'minDay' => '-1',
				'maxDay' => '30.04.2022',
			]
		)

		<br>
		@include(
			'elements.input',
			[
				'inputId' => 'datapicker',
				'label' => 'Title',
				'value' => '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => TRUE


			]
		)
	</div>
@endsection