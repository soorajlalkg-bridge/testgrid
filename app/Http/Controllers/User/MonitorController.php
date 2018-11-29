<?php
/**
 * Monitor Controller
 *
 * @category MonitorController
 * @package  Controller
 */

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Monitor Controller
 *
 * @category Class
 * @package  MonitorController
 */
class MonitorController extends Controller
{
    /**
     * Function for displaying monitor page.
     *
     * @return String
     */
    public function index()
    {
        $data = [];
        $url = str_replace('public', '', url('/')) ;
        $skimmerUrl = $url . 'storage/app/public/skimmer';
        $data['skimmerUrl'] = $skimmerUrl;//Storage::url('app/public/skimmer');
        return view('user.monitor.index', $data);
    }
}
