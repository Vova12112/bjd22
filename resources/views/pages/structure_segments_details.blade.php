@extends('pages._common')
@section('title')
	Відділ "{{ $segment->getName() }}"
@endsection

@section('content')
	<div class="own-workers-card-container">
		<h3>Структурні підрозділи</h3>
		@include(
			'elements.input',
			[
				'inputId' => 'structure_segments_details',
				'label' => 'Назва',
				'value' => '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
				'name'=>'structure-name'
			]
		)
		<button class="btn structure segments details">Зберегти</button>
	</div>
@endsection