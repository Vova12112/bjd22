<div class="input-block">
	<input type="text" class="default" placeholder=" " required id="{{ $inputId }}" value="{{ $value ?? '' }}" readonly="{{$isReadOnly}} disable="{{$isDisable}}">
	<label for="{{ $inputId }}">{{ $label ?? '' }}</label>
</div>

