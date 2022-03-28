<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');
Route::group(['prefix' => '/organization'], static function() {
	Route::get('/', 'OrganizationController@organization');
});
Route::get('/test_datapicker', 'TestController@datapicker');