<?php
/**
 * Welcome Controller
 *
 * @category WelcomeController
 * @package  Controller
 */

namespace App\Http\Controllers\ExtendedLaravelInstaller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Welcome Controller
 *
 * @category Class
 * @package  WelcomeController
 */
class WelcomeController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('extendedlaravelinstaller.welcome');
    }
}
