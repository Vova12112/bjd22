<div class="own-workers-card-container">
	<h3>Особиста карточка робітника</h3>

	<div class="own-workers-card-lastname">
		<p>Прізвище:</p>
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-last-name',
				'label' => 'Прізвище',
				'value' => '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
				'name'=>'last-name'
			]
		)
	</div>
	<div class="own-workers-card-firstname">
		<p>Ім'я:</p>
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-first-name',
				'label' => 'Ім я',
				'value' => '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
				'name'=>'first-name'
			]
		)
	</div>
	<div class="own-workers-card-surname">
		<p>По батькові:</p>
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-surname',
				'label' => 'По батькові',
				'value' => '',
				'classes' => '',
				'isReadOnly' => FALSE,
				'isDisable' => FALSE,
				'name' => 'surname'
			]
		)
	</div>
	<div class="own-workers-card-sex">
		<p>Стать:</p>
		@include(
			'elements.select',
			[
			  'id' => 'workers-details-sex',
			  'label' => '&nbsp;',
			  'labelClass' => '',
			  'isWithChoose' => TRUE,
			  'name' => 'sex',
			  'options' => ["1"=>"чоловік","2"=>"жінка"],
			]
		)
		<p>Сімейний стан:</p>
		@include(
			'elements.select',
			[
			  'id' => 'workers-details-family',
			  'label' => '&nbsp;',
			  'labelClass' => '',
			  'isWithChoose' => TRUE,
			  'name' => 'family',
			  'options' => ["1"=>"одружений","2"=>"не одружений","3" => "розлучений"],
			]
		)
	</div>
	<div class="own-workers-card-description">
		<p>День народження:</p>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker-bd',
				'selectedDay' => '',
				'minDay' => '',
				'maxDay' => \Carbon\Carbon::now('UTC')->format('d.m.Y'),
				'name' => 'date-bd'
			]
		)
		<p>Структурні підрозділи організації:</p>
		@include(
			'elements.select',
			[
				'id' => 'workers-details-organization-divisions',
				'name' => 'organization-divisions',
				'options'=>["1"=>"одружений","2"=>"не одружений","3" => "розлучений"]
			]
		)
		<p>Дата попереднього медогляду:</p>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker-medical',
				'selectedDay' => '',
				'minDay' => '',
				'maxDay' => \Carbon\Carbon::now('UTC')->format('d.m.Y'),
				'name' => 'date-medical'
			]
		)<p>Дата ввідного інструктажу:</p>
		@include(
			'elements.datapicker',
			[
				'datapickerId' => 'datapicker-instruction',
				'selectedDay' => '',
				'minDay' => '',
				'maxDay' => \Carbon\Carbon::now('UTC')->format('d.m.Y'),
				'name' => 'date-instruction'
			]
		)

	</div>
	<div class="own-workers-card-category">
		<p>Категорія:</p>
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-category',
				'label' => 'Категорія',
				'value' => '',
				'classes' => '',
				'isReadOnly' => TRUE,
				'isDisable' => FALSE,
				'name' => 'category'
			]
		)
		<div>
			<p>Професія(посада):</p>
			@include(
				'elements.input',
				[
					'inputId' => 'workers-details-position',
					'label' => 'Професія(посада)',
					'value' => '',
					'classes' => '',
					'isReadOnly' => TRUE,
					'isDisable' => FALSE,
					'name' => 'position'
				]
			)

		</div>

		<p>Додаткова інформація:</p>
		@include(
			'elements.input',
			[
				'inputId' => 'workers-details-add-information',
				'label' => 'Додаткова інформація',
				'value' => '',
				'classes' => '',
				'isReadOnly' => TRUE,
				'isDisable' => FALSE,
				'name' => 'add-information'
			]
		)
		<button class="btn workers card">Довідник</button>

	</div>

</div>