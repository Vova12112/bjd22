@extends('index')
@section('content')
<div class="container">
		<h2>Create Word File in Laravel</h2><br/>
		<form method="post" action="{{url('store')}}">
			@csrf
			<div class="row">
				<div class="col-md-4"></div>
				<div class="form-group col-md-4">
					<label for="Name">Name:
					<input type="text" class="form-control" name="name">
					</label>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4"></div>
				<div class="form-group col-md-4">
					<label for="Email">Email:
					<input type="text" class="form-control" name="email">
					</label>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4"></div>
				<div class="form-group col-md-4">
					<label for="Number">Phone Number:
					<input type="text" class="form-control" name="number">
					</label>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4"></div>
				<div class="form-group col-md-4">
					<button type="submit" class="btn btn-success">Submit</button>
				</div>
			</div>
		</form>
	</div>
@endsection