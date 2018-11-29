<?php

Route::group(['middleware' => 'checkInstall'], function () {

	Route::get('/home', function () {
	    $users[] = Auth::user();
	    $users[] = Auth::guard()->user();
	    $users[] = Auth::guard('admin')->user();

	    //dd($users);

	    return view('admin.home');
	})->name('home');

	Route::get('/users', 'Admin\UsersController@index');
	Route::post('/users/page', 'Admin\UsersController@page');
	Route::get('/users/create', 'Admin\UsersController@create');
	Route::post('/users/store', 'Admin\UsersController@store');
	Route::get('/users/edit/{id}', 'Admin\UsersController@edit')->where('id', '[0-9]+');
	Route::put('/users/update/{id}', 'Admin\UsersController@update')->where('id', '[0-9]+');
	Route::post('/users/delete', 'Admin\UsersController@delete');
	// Route::get('/users/getAccount', 'Admin\UsersController@getAccount');
	// Route::post('/users/updateAccount', 'Admin\UsersController@updateAccount');

	Route::get('/online', 'Admin\OnlineController@index');
	Route::post('/online/page', 'Admin\OnlineController@page');
	Route::get('/logs', 'Admin\LogsController@index');
	Route::post('/logs/page', 'Admin\LogsController@page');
	Route::post('/logs/export', 'Admin\LogsController@export');
	Route::get('/nodes', 'Admin\NodesController@index');
	Route::post('/nodes/page', 'Admin\NodesController@page');
	Route::get('/nodes/create', 'Admin\NodesController@create');
	Route::post('/nodes/store', 'Admin\NodesController@store');
	Route::get('/nodes/edit/{id}', 'Admin\NodesController@edit')->where('id', '[0-9]+');
	Route::put('/nodes/update/{id}', 'Admin\NodesController@update')->where('id', '[0-9]+');
	Route::post('/nodes/delete', 'Admin\NodesController@delete');

	Route::get('/profile', 'Admin\ProfileController@index');
	Route::post('/saveProfile', 'Admin\ProfileController@saveProfile');
	Route::get('/changePassword', 'Admin\ProfileController@changePassword');
	Route::post('/savePassword', 'Admin\ProfileController@savePassword');

});

