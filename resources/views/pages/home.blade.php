@extends('pages._common')
@section('title')
	Головна сторінка
@endsection

@section('content')
	<div class="container">
		@include(
		'elements.homeb', [
			'title' => 'ПРАЦІВНИКИ',
			'description' => 'Перелік працівників, додавання, редагування, звільнення.<br><br>Перелік посад та управління ними.',
			'textbtn' => 'Перейти',
			'btnClass' => 'nav-link',
			'btnAttr' => 'data-route="'. route('workers') . '"',
			]
		)
		@include(
		'elements.homeb', [
			'title' => 'ПІДПРИЄМСТВО',
			'description' => 'Перелік підприємств, додавання, редагування, видалення.<br><br>Перелік посад та управління ними.',
			'textbtn' => 'Перейти',
			'btnClass' => 'nav-link',
			'btnAttr' => 'data-route="'. route('organization') . '"',
			]
		)
		@include(
		'elements.homeb', [
			'title' => 'ТРАВМАТИЗМ',
			'description' => 'Перелік травм, додавання, редагування, видалення.<br><br>Перелік типів, видів, причин.',
			'textbtn' => 'Перейти'
			]
		)


	</div>

@endsection