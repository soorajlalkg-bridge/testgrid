<?php
Route::group(['middleware' => 'checkInstall'], function () {
    Route::get('/home', function () {
        $users[] = Auth::user();
        $users[] = Auth::guard()->user();
        $users[] = Auth::guard('igadmin')->user();

        //dd($users);

        return view('igadmin.home');
    })->name('home');

    Route::get('/dashboard', 'Igadmin\DashboardController@index');
    Route::post('/saveSettings', 'Igadmin\DashboardController@saveSettings');
});
