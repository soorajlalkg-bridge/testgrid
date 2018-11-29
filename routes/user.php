<?php

Route::group(['middleware' => 'checkInstall'], function () {
	Route::get('/home', function () {
	    $users[] = Auth::user();
	    $users[] = Auth::guard()->user();
	    $users[] = Auth::guard('user')->user();

	    //dd($users);

	    //return view('user.home');
	    return view('home');
	})->name('home');

	Route::get('/configure', 'User\ConfigureController@index')->name('configure');
	Route::get('/grid', 'User\GridController@index')->name('grid');
	Route::get('/monitor', 'User\MonitorController@index')->name('monitor');
	Route::post('/importCfg', 'User\ConfigureController@importCfg');
	Route::post('/copyConfig', 'User\ConfigureController@copyConfig');
	Route::post('/createConfig', 'User\ConfigureController@createConfig');
	Route::post('/updateConfig', 'User\ConfigureController@updateConfig');
	Route::post('/actionConfigure', 'User\ConfigureController@actionConfigure');
	Route::post('/getConfigGroup', 'User\ConfigureController@getConfigGroup');
	Route::post('/createConfigGroup', 'User\ConfigureController@createConfigGroup');
	Route::post('/deleteConfigGroup', 'User\ConfigureController@deleteConfigGroup');
	Route::post('/addRemoveConfigGroup', 'User\ConfigureController@addRemoveConfigGroup');
	Route::post('/getConfig', 'User\ConfigureController@getConfig');
	Route::post('/swapConfigGroupChannels', 'User\ConfigureController@swapConfigGroupChannels');
	Route::post('/grid', 'User\GridController@index');
	Route::post('/toggleAutoPlay', 'User\SettingsController@toggleAutoPlay');

	Route::get('/getProfile', 'User\ProfileController@getProfile');
	Route::post('/saveProfile', 'User\ProfileController@saveProfile');

	Route::post('/lastActivity', 'User\ProfileController@lastActivity');

	Route::get('/test', 'User\TestController@index');

});