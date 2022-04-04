<?php

use App\Models\Controllers\AccidentsModelController;
use App\Models\Controllers\OrganizationsModelController;
use App\Models\Repo\AccidentsModelRepo;
use App\Models\Repo\OrganizationsModelRepo;
use App\Models\WorkerAccident;

/*** @var WorkerAccident $accident */
$accidentController     = new AccidentsModelController(new AccidentsModelRepo());
$organizationController = new OrganizationsModelController(new OrganizationsModelRepo());
?>

@extends('pages._common')
@section('title')
	{{ isset($accident) ? 'Інцидент №' . $accident->getId() : 'Новий інцидент' }}
@endsection

@section('content')
	<style type="text/css">
		.content-wrapper .input-block {
			margin: 25px 0;
		}
		.content-wrapper .action-row {
			margin:          35px 0;
			text-align:      center;
			display:         flex;
			justify-content: space-between;
		}
		.content-wrapper .period-datepicker {
			width:   48%;
			display: inline-block;
		}
	</style>
	<form action="{{ route('accident.save', ['id' => isset($accident) ? $accident->getId() : -1]) }}" method="POST">
		@csrf
		@if ( isset($accident))
			@include(
				'elements.input',
				[
					'inputId' => 'accident-details-worker',
					'label' => 'Працівник',
					'name'=>'full_name',
					'value' => isset($accident) ? $accident->worker->getFullName() : '',
					'old' => isset($accident) ? $accident->worker->getFullName() : '',
					'classes' => '',
					'isReadOnly' => TRUE,
					'isDisable' => FALSE
				]
			)
		@else
			@include(
				'elements.select',
				[
					'id' => 'workers-accidents-worker',
					'classes' => 'filter-input',
					'label' => 'Працівник',
					'selected' => '',
					'labelClass' => '',
					'isWithChoose' => TRUE,
					'name' => 'worker_id',
					'options' => \App\ValuesObject\Worker::getWorkers(),
				]
			)
		@endif
		@if( isset($accident) )
			<div style="margin: -15px 0 0 0; width: 100%">
				<button id="js-go-to-worker" class="btn nav-link" data-route="{!! route('worker.details', ['id' => $accident->worker->getId()]) !!}">Перейти до працівника</button>
			</div>
		@endif

		@include(
			'elements.select',
			[
				'id' => 'workers-accidents-type',
				'classes' => 'filter-input',
				'label' => 'Тип інциденту',
				'selected' => isset($accident) ? $accident->getAccidentTypeId() : '',
				'labelClass' => '',
				'isWithChoose' => TRUE,
				'name' => 'accidents_type_id',
				'options' => \App\ValuesObject\AccidentType::getAccidentTypes(),
			]
		)

		<h4 style="margin-bottom: -20px">Дата інцеденту</h4>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'accident-details-accident-at',
				'placeholder' => 'Дата інциденту',
				'selectedDay' => isset($accident) ? $accident->getAccidentAt()->format('d.m.Y') : '',
				'old' => isset($accident) ? $accident->getAccidentAt()->format('d.m.Y') : '',
				'minDay' => '',
				'maxDay' => now('UTC')->format('d.m.Y'),
				'name' => 'accident_at'
			]
		)


		@include(
			'elements.input',
			[
				'inputId' => 'accident-details-hours',
				'label' => 'Кількість годин після початку роботи',
				'name'=>'hours',
				'value' => isset($accident) ? $accident->getHoursAfterStartWorking() : '',
				'old' => isset($accident) ? $accident->getHoursAfterStartWorking() : '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE
			]
		)

		<h4 style="margin-bottom: -20px">Лікарняне (від-до)</h4>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'workers-accidents-sick-start',
				'placeholder' => 'Від',
				'class' => 'period-datepicker',
				'selectedDay' => isset($accident) && $accident->getSickStartAt() ? $accident->getSickStartAt()->format('d.m.Y') : '',
				'old' => isset($accident) && $accident->getSickStartAt() ? $accident->getSickStartAt()->format('d.m.Y') : '',
				'minDay' => '',
				'maxDay' => now('UTC')->format('d.m.Y'),
				'name' => 'sick_start_at'
			]
		)
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'workers-accidents-sick-end',
				'placeholder' => 'До',
				'class' => 'period-datepicker',
				'selectedDay' => isset($accident) && $accident->getSickEndAt() ? $accident->getSickEndAt()->format('d.m.Y') : '',
				'old' => isset($accident) && $accident->getSickEndAt() ? $accident->getSickEndAt()->format('d.m.Y') : '',
				'name' => 'sick_end_at'
			]
		)

		<div class="action-row">

			@if ( isset($accident))
				<div class="actions-group" style="display: inline-block;">
					<button class="btn js-word-template" data-template="info1" data-name-pref="Povidomlennya-">Повідомлення про інц.</button>
					<button class="btn js-word-template" data-template="protokol1" data-name-pref="Protokol-opytuvannya-">Протокол опитування</button>
				</div>
			@endif
			<button class="btn js-save-category">Зберегти</button>
		</div>
	</form>

	<script type="text/javascript">
		$(document).ready(function () {
			const
				$page = $(".content-wrapper"),
				$inputs = $page.find("input, select"),
				$goToWorkerBtn = $page.find("#js-go-to-worker"),
				$templatesBtn = $page.find(".js-word-template"),
				$saveBtn = $page.find(".js-save-category")
			;

			$templatesBtn.on("click", function (e) {
				e.preventDefault();
				ajaxRequest(
					"{{ route('template.render') }}",
					"POST",
					"json",
					{
						"template_name": $(this).data("template"),
						"file_name"    : $(this).data("name-pref"),
						"params"       : {
							"organizationName"   : "{{ $organizationController->getOrganization()->getName() }}",
							"organizationAddress": "{{ $organizationController->getOrganization()->getAddress() }}",
							"accidentAt"         : "{{ isset($accident) ? $accident->getAccidentAt()->format('d.m.Y') : '' }}",
							"workerFullName"     : "{{ isset($accident) ? $accident->worker->getFullName() : '' }}",
							"workerProfession"   : "{{ isset($accident) ? $accident->worker->profession->getName() : '' }}",
							"workerMarried"   : "{{ isset($accident) && $accident->worker->isMarried() ? 'заміжній' : 'незаміжній' }}",
							"workerBirth"        : "{{ isset($accident) ? $accident->worker->getBirthAt() : '' }}",
							"workerSegment"      : "{{ isset($accident) ? $accident->worker->structureSegment->getName() : '' }}",
							"day"                : "{{ now()->day }}",
							"monthWord"          : "{{ now()->month }}",
							"year"               : "{{ now()->year }}",
						}
					}
				)
			});

			$goToWorkerBtn.on("click", function (e) {
				e.preventDefault();
			});

		});
	</script>

@endsection