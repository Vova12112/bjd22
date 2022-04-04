<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');
Route::group(['prefix' => '/organization'], static function() {
	Route::get('/', 'OrganizationController@organization')->name('organization');
	Route::post('/save', 'OrganizationController@save')->name('organization.save');
	Route::group(['prefix' => '/segments'], static function() {
		Route::get('/', 'OrganizationController@organizationStructure')->name('organization.segments');
		Route::post('/redirect', 'OrganizationController@redirect')->name('segment.details.redirect');
		Route::get('/details/{id}', 'OrganizationController@segmentDetails')->name('segment.details');
		Route::post('/save/{id}', 'OrganizationController@segmentSave')->name('segment.save');
		Route::post('/delete/{id}', 'OrganizationController@segmentDelete')->name('segment.delete');
	});
});
Route::group(['prefix' => '/workers'], static function() {
	Route::get('/', 'WorkerController@workers')->name('workers');
	Route::get('/create', 'Action\WorkerActionController@get')->name('worker.create.page');
	Route::get('/delete/id', 'Action\WorkerActionController@deleteWorker')->name('worker.delete.page');
	Route::post('/save', 'Action\WorkerActionController@addNewWorker')->name('worker.save.page');
	Route::post('/details/worker/redirect', 'WorkerController@redirect')->name('worker.details.redirect');
	Route::get('/details/{id}', 'WorkerController@workerDetails')->name('worker.details');
	Route::group(['prefix' => '/professions'], static function() {
		Route::get('/', 'ProfessionController@professions')->name('professions');
		Route::post('/save/{id}', 'ProfessionController@save')->name('profession.save');
		Route::post('/delete/{id}', 'ProfessionController@delete')->name('profession.delete');
		Route::post('/details/profession/redirect', 'ProfessionController@redirect')->name('profession.details.redirect');
		Route::get('/details/{id}', 'ProfessionController@professionDetails')->name('profession.details');
		Route::group(['prefix' => '/categories'], static function() {
			Route::get('/', 'ProfessionController@categories')->name('categories');
			Route::post('/details', 'ProfessionController@categoryDetails')->name('category.details');
			Route::post('/save/{id}', 'ProfessionController@categorySave')->name('category.save');
			Route::post('/delete', 'ProfessionController@categoryDelete')->name('category.delete');
		});
	});
});
Route::group(['prefix' => '/accidents'], static function() {
	Route::get('/', 'AccidentController@show')->name('accidents.show');
	Route::get('/workers', 'AccidentController@accidentWorkers')->name('accidents.workers');
	Route::post('/details/redirect', 'AccidentController@accidentWorkers')->name('accident.details.redirect');
	Route::get('/details', 'AccidentController@accidentWorkers')->name('accident.details');
});
Route::group(['prefix' => '/paginator'], static function() {
	Route::post('/workers', 'PaginatorController@workers')->name('paginator.workers');
	Route::post('/segments', 'PaginatorController@segments')->name('paginator.segments');
	Route::post('/accidents', 'PaginatorController@accidents')->name('paginator.accidents');
	Route::post('/professions', 'PaginatorController@professions')->name('paginator.professions');
	Route::post('/categories', 'PaginatorController@categories')->name('paginator.categories');
	Route::post('/workers_accidents', 'PaginatorController@workersAccidents')->name('paginator.workers-accidents');
});
Route::get('/test_datapicker', 'TestController@datapicker');
Route::get('/create', 'WordController@create');
Route::post('/store', 'TemplateController@saveWord');
Route::post('/add-in', 'WordController@writeInDocOpen');
Route::get('/file-chose', function() {
	return view('docpicker');
});
Route::group(['prefix' => '/error'], static function() {
	Route::get('/unexpected', 'HomeController@unexpectedError')->name('error.unexpected');
});