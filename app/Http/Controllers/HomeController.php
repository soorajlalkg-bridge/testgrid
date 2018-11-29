<?php
/**
 * Home Controller
 *
 * @category HomeController
 * @package  Controller
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Home Controller
 *
 * @category Class
 * @package  HomeController
 */
class HomeController extends Controller
{

    /**
     * Function for displaying home page.
     *
     * @return String
     */
    public function getHome()
    {
        $data = [];

        return view('home', $data);
    }
}
