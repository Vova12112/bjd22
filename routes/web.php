<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::group(['prefix' => '/organization'], static function() {
	Route::get('/', 'OrganizationController@organization');
});
Route::get('/test_datapicker', 'TestController@datapicker');
Route::get('/create','WordController@create');
Route::post('/store','WordController@store');
Route::post('/add-in','WordController@writeInDocOpen');
Route::get('/file-chose', function () {
	return view('docpicker');
});