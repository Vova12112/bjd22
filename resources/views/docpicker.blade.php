@extends('index')
@section('content')
<div class="custom-file-upload">
	<label for="file">File:
	<input type="file" id="file" name="myfiles[]" multiple />
	</label>
</div>
@endsection