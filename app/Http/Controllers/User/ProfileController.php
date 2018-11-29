<?php
/**
 * Profile Controller
 *
 * @category ProfileController
 * @package  Controller
 */

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Session;
use App\Library\CustomLog;
use Carbon\Carbon;

/**
 * Profile Controller
 *
 * @category Class
 * @package  ProfileController
 */
class ProfileController extends Controller
{

    /**
     * Function for getting profile data.
     *
     * @return response
     */
    public function getProfile()
    {
        $data = [];
        $currentUser = \Auth::guard('user')->user();

        return response()->json(['status' => 'success', 'user'=>$currentUser], 200);
    }

    /**
     * Function for save profile data.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return response
     */
    public function saveProfile(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        $id = $currentUser->id;

        $validator = \Validator::make(
            $request->all(), [
            'username' => 'required|min:3|max:100|unique:user,username,'.$id,
            'email' => 'email|required|min:3|max:100|unique:user,email,'.$id,
            'firstname' => 'required|min:3|max:50',
            'lastname' => 'required|min:1|max:50',
            'password' => 'confirmed',
            ]
        );
        if ($validator->passes()) {

            $currentUser = \Auth::guard('user')->user();
            $currentUser->username = $request->get('username');
            $currentUser->firstname = $request->get('firstname');
            $currentUser->lastname = $request->get('lastname');
            $currentUser->email = $request->get('email');
            if ($request->has('password') && $request->get('password')) {
                $currentUser->pwhash = bcrypt($request->get('password'));
            }
            $currentUser->save();

            //save log
            CustomLog::saveLog('', 'save config');

            return response()->json(['status' => 'success', 'message'=>'Profile has been updated successfully.'], 200);
        }
        return response()->json(['status'=>'failure','errors'=>$validator->getMessageBag()->toArray()]);
    }

    /**
     * Function for update last activity.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return response
     */
    public function lastActivity(Request $request)
    {
        $currentUser = \Auth::guard('user')->user();
        
        if ($request->session()->has('sessionToken') && $currentUser) {
            $sessionToken = $request->session()->get('sessionToken');
            //check in db
            $sessionInfo = Session::where('userid', '=', $currentUser->id)->where('session_token', '=', $sessionToken)->whereNull('end')->first();
            
            if ($sessionInfo) {
                $sessionId = $sessionInfo->id;

                //save session
                $lastActivity = Carbon::now()->toDateTimeString();
                Session::where('id', $sessionId)->update(array('last_activity' => $lastActivity));

                return response()->json(['status' => 'success'], 200);
            }
        }

        return response()->json(['status' => 'failure'], 200);
    }
}
