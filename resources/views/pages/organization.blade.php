@extends('pages._common')
@section('title')
	Відомості про організацію
@endsection

@section('content')
	<div class="conteiner">
		<div class="orgName">
			<p style="width: 140px">Назва фірми:</p>
			<input type="text" class="name">
		</div>
		<div class="orgName">
			<p style="width: 140px">Адреса:</p>
			<input type="text" class="name2">
		</div>
		<div id="orgbtn">
			<button class="btn">Зберегти</button>
		</div>

	</div>
@endsection