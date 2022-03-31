<div class="textarea">
	<label for="{{ $inputId }}">{{ $label ?? '' }}
		<textarea
		class="default {{ $classes }}"
		placeholder="{{isset($placeholder)?'Поле для нотаток':$placeholder}}"
		{{isset($isRequired) && $isRequired}}
		{{ isset($isReadOnly) && $isReadOnly ? 'readonly' : '' }}
		{{ isset($isDisable) && $isDisable ? 'disable' : '' }}
		name="{{ $name ?? '' }}">
	</textarea>
	</label>
</div>
