<?php
/**
 * Grid Controller
 *
 * @category GridController
 * @package  Controller
 */

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Config;
use App\ConfigGroup;
use App\ConfigChannel;

/**
 * Grid Controller
 *
 * @category Class
 * @package  GridController
 */
class GridController extends Controller
{
    /**
     * Function for displaying grid page.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function index(Request $request)
    {
        $data = [];
        $currentUser = \Auth::guard('user')->user();
    
        $gridConfig = session('gridConfig');
        $gridConfigGroup = session('gridConfigGroup');
        $cfgFile = Config::with('configgroup')->where('id', '=', $gridConfig)->where('userid', '=', $currentUser->id)->orderBy('id', 'desc')->first();
        if ($cfgFile) {
            $firstCfgGroup = $cfgFile->configgroup->first();
            $firstCfgGroupId = null;
            if ($gridConfig && $gridConfigGroup) {
                $firstCfgGroup = $cfgFile->configgroup()->find($gridConfigGroup);
                $firstCfgGroupId = $firstCfgGroup->id;
            }

            $data['gridConfigGroup'] = '';
            if ($request->has('grid-config-groups')) {
                $firstCfgGroup = $cfgFile->configgroup->find($request->get('grid-config-groups'));
                $data['gridConfigGroup'] = $request->get('grid-config-groups');
            }

            if ($firstCfgGroup) {
                $cfgFile->firstconfiggroup = $firstCfgGroup;
                $cfgFile->selectedCfgGroupId = $firstCfgGroupId;
            }
        } else {
            session()->flash('error', 'No configuration is created!');
        }

        $data['gridPlayerMode'] = 'fixed';
        if ($request->has('grid-player-mode')) {
            $data['gridPlayerMode'] = $request->get('grid-player-mode');
        }

        $data['cfgFile'] = $cfgFile;
        $data['gridView'] = true;

        return view('user.grid.index', $data);
    }
}
