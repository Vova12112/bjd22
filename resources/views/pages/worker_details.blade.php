<?php
	/*** @var \App\Models\Worker $worker */
?>

@extends('pages._common')
@section('title')
	{{ $worker->getFullName() }}
@endsection

@section('content')
	@include(
	'elements.ownworkerscard'
	)
@endsection
