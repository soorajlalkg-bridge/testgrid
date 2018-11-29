<?php
/**
 * Settings Controller
 *
 * @category SettingsController
 * @package  Controller
 */

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Settings Controller
 *
 * @category Class
 * @package  SettingsController
 */
class SettingsController extends Controller
{
    /**
     * Function for save autoplay settings.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return response
     */
    public function toggleAutoPlay(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        $id = $currentUser->id;

        $validator = \Validator::make(
            $request->all(), [
            'autoplay' => 'required',
            ]
        );
        if ($validator->passes()) {
        	$toggleAutoPlay = ($request->get('autoplay')=='true') ? 1 : 0;

            $currentUser = \Auth::guard('user')->user();
            $currentUser->autoplay = $toggleAutoPlay;
            $currentUser->save();

            return response()->json(['status' => 'success', 'message'=>'Settings has been updated successfully.','data'=>['autoplay'=>$toggleAutoPlay]], 200);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->getMessageBag()->toArray()]);
    }
}
