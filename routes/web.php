<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');

Route::group(['prefix' => '/organization'], static function() {
	Route::get('/', 'OrganizationController@organization');
});
Route::group(['prefix' => '/workers'], static function() {
	Route::get('/', 'WorkerController@workers');
	Route::post('/details/worker/redirect', 'WorkerController@redirect')->name('worker.details.redirect');
	Route::get('/details/{id}', 'WorkerController@workerDetails')->name('worker.details');
});

Route::group(['prefix' => '/paginator'], static function() {
	Route::post('/workers', 'PaginatorController@workers')->name('paginator.workers');
});

Route::get('/test_datapicker', 'TestController@datapicker');
Route::get('/create','WordController@create');
Route::post('/store','TemplateController@saveWord');
Route::post('/add-in','WordController@writeInDocOpen');
Route::get('/file-chose', function () {
	return view('docpicker');
});