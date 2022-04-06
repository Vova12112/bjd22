<?php
/*** @var \App\Models\Worker $worker */
?>


<div class="own-workers-card-container">
	<input type="hidden" name="id" value="{{isset($worker)?$worker->getId():'-1'}}">
	<h3>Особиста картка робітника</h3>
	<div class="own-workers-card-lastname">
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-last-name',
				'label' => 'Прізвище *',
				'value' => isset($worker)?$worker->getLastName():'',
				'old'=>isset($worker)??$worker->getLastName(),
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
				'name'=>'last-name'
			]
		)
	</div>
	<div class="own-workers-card-firstname">
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-first-name',
				'label' => 'Ім я *',
				'value' => isset($worker)?$worker->getFirstName():'',
				'old'=>isset($worker)??$worker->getFirstName(),
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
				'name'=>'first-name'
			]
		)
	</div>
	<div class="own-workers-card-surname">
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-surname',
				'label' => 'Побатькові',
				'value' => isset($worker)?$worker->getSubName():'',
				'old'=>isset($worker)??$worker->getSubName(),
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
				'name' => 'sub-name'
			]
		)
	</div>
	<div class="own-workers-card-sex">
		@include(
			'elements.select',
			[
			  'id' => 'workers-details-sex',
			  'label' => 'Стать',
			  'value' => isset($worker)?$worker->getSex():'',
			  'selected' => isset($worker)&&$worker->getSex()?$worker->getSex():'0',
			  'old'=>isset($worker)??$worker->getSex(),
			  'labelClass' => '',
			  'isWithChoose' => TRUE,
			  'name' => 'sex',
			  'options' => $sexes,
			]
		)
		@include(
			'elements.select',
			[
			  'id' => 'workers-details-family',
			  'label' => 'Сімейний стан *',
			  'old'=>isset($worker) && $worker->isMarried() ? '1' : '0',
			  'selected' => isset($worker) && $worker->isMarried() ? '1' : '0',
			  'labelClass' => '',
			  'name' => 'married',
			  'options' => $marryStatuses,
			]
		)
	</div>
	<div class="own-workers-card-description">
		<p>День народження</p>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker-bd',
				'selectedDay' => isset($worker)&&$worker->getBirthAt() !== NULL?(string)$worker->getBirthAt()->format('d.m.Y'):'',
			    'old'=>isset($worker)??(string)$worker->getBirthAt(),
				'minDay' => '',
				'maxDay' => \Carbon\Carbon::now('UTC')->format('d.m.Y'),
				'name' => 'birth-at'
			]
		)
		@include(
			'elements.select',
			[
				'id' => 'workers-details-organization-divisions',
				'name' => 'structure-segment-id',
			    'label' => 'Структурні підрозділи організації *',
				'selected' => isset($worker)?$worker->getStructureSegmentId():'-',
			    'old'=>isset($worker)??$worker->getStructureSegmentId(),
			    'isWithChoose' => TRUE,
				'options'=>$divisions,
			]
		)
		<p>Дата попереднього медогляду:</p>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker-medical',
				'selectedDay' => isset($worker)&&$worker->getBodyCheckAt() !== NULL?(string)$worker->getBodyCheckAt()->format('d.m.Y'):'',
			    'old'=>isset($worker)??(string)$worker->getBodyCheckAt(),
				'minDay' => '',
				'maxDay' => \Carbon\Carbon::now('UTC')->format('d.m.Y'),
				'name' => 'body-check-at'
			]
		)<p>Дата ввідного інструктажу:</p>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker-instruction',
				'selectedDay' => isset($worker)&&$worker->getInstructedAt() !== NULL?(string)$worker->getInstructedAt()->format('d.m.Y'):'',
			    'old'=>isset($worker)??(string)$worker->getInstructedAt(),
				'minDay' => '',
				'maxDay' => \Carbon\Carbon::now('UTC')->format('d.m.Y'),
				'name' => 'instructed-at'
			]
		)

	</div>
	<div class="own-workers-card-category">
		<div>
			@include(
			'elements.select',
			[
				'label' => 'Професія(посада) *',
				'id' => 'workers-details-position',
				'name' => 'profession-id',
				'selected' => isset($worker)?$worker->getProfessionId():'-',
			    'old'=>isset($worker)?$worker->getProfessionId():'-',
				'options'=>$professions,
			    'isWithChoose' => TRUE,
			]
		)
		</div>
		@include(
			'elements.textarea',
			[
				'inputId' => 'workers-details-add-information',
				'label' => 'Додаткова інформація',
				'value' => isset($worker)?$worker->getDescription():'',
			    'old'=>isset($worker)??$worker->getDescription(),
				'classes' => '',
				'isDisable' => FALSE,
				'name' => 'description',
				'placeholder'=>''
			]
		)
	</div>

</div>
