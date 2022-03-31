<div class="input-block">
	<input type="text"
			class="default {{ $classes }}"
			placeholder=" "
			{{isset($isRequired) && $isRequired}}
			value="{{ $value ?? '' }}"
			{{ isset($isReadOnly) && $isReadOnly ? 'readonly' : '' }}
			{{ isset($isDisable) && $isDisable ? 'disable' : '' }}
			name="{{ $name ?? '' }}">

	<label for="{{ $inputId }}">{{ $label ?? '' }}</label>
</div>

