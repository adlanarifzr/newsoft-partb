<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function() {
	Route::get('/home', 'HomeController@index')->name('home');

	Route::prefix('/users')->group(function() {
		Route::get('/', 'UserController@index')->name('users');
		Route::post('/', 'UserController@insert');

		Route::prefix('/{id}')->group(function() {
			Route::get('/', 'UserController@view')->name('users.item');
			Route::patch('/', 'UserController@update');
			Route::delete('/', 'UserController@delete');
		});
	});

	Route::prefix('/listings')->group(function() {
		Route::get('/', 'ListingController@index')->name('listings');
		Route::post('/', 'ListingController@insert');

		Route::prefix('/{id}')->group(function() {
			Route::get('/', 'ListingController@view')->name('listings.item');
			Route::patch('/', 'ListingController@update');
			Route::delete('/', 'ListingController@delete');
		});
	});
});