<div class="input-block {{ $classes ?? '' }}">
	<input type="text"
			class="default"
			placeholder=" "
			{{isset($isRequired) && $isRequired}}
			value="{{ $value ?? '' }}"
			{{ isset($isReadOnly) && $isReadOnly ? 'readonly' : '' }}
			{{ isset($isDisable) && $isDisable ? 'disable' : '' }}
			name="{{ $name ?? '' }}"
			data-old="{{ $old ?? ''  }}"
	>
	<label for="{{ $inputId }}">{{ $label ?? '' }}</label>
</div>

