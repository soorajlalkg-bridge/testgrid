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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::group(['middleware' => 'checkInstall'], function () {

  Route::get('/', 'HomeController@getHome');

  Route::group(['prefix' => 'admin'], function () {
    Route::get('/login', 'AdminAuth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'AdminAuth\LoginController@login');
    Route::post('/logout', 'AdminAuth\LoginController@logout')->name('logout');

    Route::get('/register', 'AdminAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'AdminAuth\RegisterController@register');

    Route::post('/password/email', 'AdminAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
    Route::post('/password/reset', 'AdminAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'AdminAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'AdminAuth\ResetPasswordController@showResetForm');
    Route::get('/logoutAccount', 'AdminAuth\LoginController@logoutAccount');
  });

  Route::group(['prefix' => 'user'], function () {
    //Route::get('/login', 'UserAuth\LoginController@showLoginForm')->name('login');
    Route::get('/login', 'HomeController@getHome')->name('login');
    Route::post('/login', 'UserAuth\LoginController@login');
    Route::post('/logout', 'UserAuth\LoginController@logout')->name('logout');

    //Route::get('/register', 'UserAuth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'UserAuth\RegisterController@register');

    Route::post('/password/email', 'UserAuth\ForgotPasswordController@sendResetLinkEmail')->name('password.request');
    Route::post('/password/reset', 'UserAuth\ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'UserAuth\ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'UserAuth\ResetPasswordController@showResetForm');

    Route::post('/loginAccount', 'UserAuth\LoginController@loginAccount');
    Route::get('/logoutAccount', 'UserAuth\LoginController@logoutAccount');
  });

  Route::group(['prefix' => 'igadmin'], function () {
    Route::get('/login', 'IgadminAuth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'IgadminAuth\LoginController@login');
    Route::post('/logout', 'IgadminAuth\LoginController@logout')->name('logout');
    Route::get('/logoutAccount', 'IgadminAuth\LoginController@logoutAccount');
  });

});

Route::group(['prefix' => 'install','as' => 'LaravelInstaller::','namespace' => 'ExtendedLaravelInstaller','middleware' => ['web', 'install']], function() {

    Route::get('/', [
        'as' => 'welcome',
        'uses' => 'WelcomeController@welcome'
    ]);

    Route::get('database', [
        'as' => 'database',
        'uses' => 'DatabaseController@database'
    ]);

    Route::post('environment/saveWizard', [
        'as' => 'environmentSaveWizard',
        'uses' => 'EnvironmentController@saveWizard'
    ]);

    Route::get('environment/wizard', [
        'as' => 'environmentWizard',
        'uses' => 'EnvironmentController@environmentWizard'
    ]);
    
    Route::get('environment/saveWizard', function () {
        return redirect('install/environment/wizard');
    });

    Route::get('credentials', [
        'as' => 'credentials',
        'uses' => 'CredentialsController@credentials'
    ]);

    Route::post('credentials/saveCredentials', [
        'as' => 'credentialsSaveCredentials',
        'uses' => 'CredentialsController@saveCredentials'
    ]);

    Route::get('credentials/saveCredentials', function () {
        return redirect('install/credentials');
    });

    Route::get('igadmin', [
        'as' => 'igadmin',
        'uses' => 'IgadminCredentialsController@credentials'
    ]);

    Route::post('igadmin/saveCredentials', [
        'as' => 'igadminCredentialsSaveCredentials',
        'uses' => 'IgadminCredentialsController@saveCredentials'
    ]);

    Route::get('igadmin/saveCredentials', function () {
        return redirect('install/igadmin');
    });

    Route::get('licenses', [
        'as' => 'licenses',
        'uses' => 'LicensesController@licenses'
    ]);

    Route::post('licenses/saveLicenses', [
        'as' => 'licensesSaveLicenses',
        'uses' => 'LicensesController@saveLicenses'
    ]);

    Route::get('licenses/saveLicenses', function () {
        return redirect('install/licenses');
    });

    Route::get('final', [
        'as' => 'final',
        'uses' => 'FinalController@finish'
    ]);

});