<?php
/**
 * Login Controller
 *
 * @category LoginController
 * @package  Controller
 */

namespace App\Http\Controllers\UserAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Http\Request;
use App\Session;
use App\Library\CustomLog;
use App\Library\ClientIp;
use Carbon\Carbon;

/**
 * Login Controller
 *
 * @category Class
 * @package  LoginController
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/user/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('user.guest', ['except' => ['logout','loginAccount','logoutAccount']]);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }

    /**
     * Function for login.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function loginAccount(Request $request)
    {

        $validator = \Validator::make(
            $request->all(), [
            'username' => 'required',
            'password' => 'required',
            ]
        );
        if ($validator->passes()) {
            //validate user
            $auth = false;
            $credentials = $request->only('username', 'password');
            
            //@todo - check simultaneous login
            
            //allow login using username or email
            $username = $request->username;
            $password = $request->password;
            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                //user sent their email 
                if (Auth::guard('user')->attempt(['email' => $username, 'password' => $password], $request->has('remember'))) {
                    $auth = true; 
                }
            } else {
                //they sent their username instead 
                if (Auth::guard('user')->attempt(['username' => $username, 'password' => $password], $request->has('remember'))) {
                    $auth = true; 
                }
            }

            if ($auth == true) {
                //if (Auth::guard('user')->attempt($credentials, $request->has('remember'))) {
                //$auth = true; // Success
                
                $currentUser = Auth::guard('user')->user();
                
                $sessionToken = bin2hex(random_bytes(16));

                //check simultaneous login
                //@todo - get new session_id after user sign in
                $sessionToken   = \Session::getId();
                //$sessionCount = Session::where('userid', '=', $currentUser->id)->whereNull('end')
                //    ->count();

                //get active sessions
                $sessionLifeTime = config('session.lifetime');

                $now = Carbon::now()->toDateTimeString();
                $sessionCount = Session::where('userid', '=', $currentUser->id)
                                ->whereNull('end')
                                ->whereRaw('TIMESTAMPDIFF(MINUTE, last_activity, ?) < ?', [$now, $sessionLifeTime])
                                ->count();

                //check session inactive but not logged out(in case broswer close and system shutdown)
                $sessionInactive = Session::where('userid', '=', $currentUser->id)
                                ->whereNull('end')
                                ->whereRaw('TIMESTAMPDIFF(MINUTE, last_activity, ?) >= ?', [$now, $sessionLifeTime])
                                ->get();

                //get all active sessions
                $sessionActive = Session::where('userid', '=', $currentUser->id)
                                ->whereNull('end')
                                ->get();
                $sessionActiveCount = $sessionActive->count();
                $sessionInactiveCount = 0;   
                $saveLogoutLog = false;                       
                if ($sessionActive) {
                    foreach ($sessionActive as $userActiveSession) {
                        $activeSessionId = $userActiveSession->id;
                        $session = Session::find($activeSessionId);

                        $now = Carbon::now();
                        $timestamp = $now;

                        // retrive session
                        $lastSession = \Session::getHandler()->read($userActiveSession->session_token); 
                        //print_r($lastSession);
                        if (!$lastSession) {
                            //session destroyed, but not updated db
                            
                            $lastActivity = ($session->last_activity==NULL)?$now:$session->last_activity;
                            // session was destroyed
                            $session->last_activity = $lastActivity;
                            $session->end = $lastActivity;
                            $session->save();

                            $saveLogoutLog = true;
                            $sessionInactiveCount++;
                        } else {
                            //session not destroyed
                            //@todo - check > session life time
                            $lastActivity = ($session->last_activity==NULL)?$session->start:$session->last_activity;

                            $startTime = Carbon::parse($lastActivity);
                            $finishTime = Carbon::now();
                            $totalDuration = $finishTime->diffInSeconds($startTime);
                            $inactiveTime = ($totalDuration/3600);

                            if ($inactiveTime > $sessionLifeTime) {
                                \Session::getHandler()->destroy($userInactiveSession->session_token);
                                // session was destroyed
                                $session->last_activity = $lastActivity;
                                $session->end = $lastActivity;
                                $session->save();

                                $saveLogoutLog = true;
                                $sessionInactiveCount++;
                            }
                        }

                        if ($saveLogoutLog == true) {
                            //save log
                            //@todo - check already updated
                            $checkDuplicate = true;
                            CustomLog::saveLog($activeSessionId, 'logout', $checkDuplicate);
                        }
                    }
                }
                $sessionCount = $sessionActiveCount - $sessionInactiveCount;
                //return response()->json(['status'=>'failure','errors'=>[$sessionInactiveCount],'sessionActive'=>$sessionActive]);
                
                /*if ($sessionInactive) {
                    foreach ($sessionInactive as $userInactiveSession) {
                        $inactiveSessionId = $userInactiveSession->id;
                        $session = Session::find($inactiveSessionId);

                        // retrive last session
                        $lastSession = \Session::getHandler()->read($userInactiveSession->session_token); 
                        if ($lastSession) {
                            \Session::getHandler()->destroy($userInactiveSession->session_token);
                        }
                        // session was destroyed
                        $session->end = $session->last_activity;
                        $session->save();

                        //save log
                        //@todo - check already updated
                        $checkDuplicate = true;
                        CustomLog::saveLog($inactiveSessionId, 'logout', $checkDuplicate);
                    }
                }*/

                $loginLimit = $currentUser->loginlimit;
                if ($sessionCount >= $loginLimit) {
                    //@todo - logout current session
                    Auth::guard('user')->logout();
                    //Maximum no. of active logins found for this account. Please logout from another device to continue.
                    return response()->json(['status'=>'failure','errors'=>['Maximum no. of active logins found for this account.']]);
                }

                //login

                // Via a request instance...
                $request->session()->put('sessionToken', $sessionToken);

                $clientIp = ClientIp::getIpAddress();

                //save session
                $input['userid'] = $currentUser->id;
                $input['session_token'] = $sessionToken;
                $input['start'] = Carbon::now()->toDateTimeString(); //timestamp
                $input['ip'] = $clientIp;
                $session = Session::create($input);

                $sessionId = $session['id'];

                //save log
                CustomLog::saveLog($sessionId, 'login');
                
                return response()->json(['status'=>'success','success'=>'Logged in.','redirect'=>url('/user/configure')]);
            }

            return response()->json(['status'=>'failure','errors'=>['These credentials do not match our records.']]);
        }
        return response()->json(['status'=>'failure','errors'=> $validator->getMessageBag()->toArray()]);
    }

    /**
     * Function for logout.
     *
     * @param \Illuminate\Http\Request $request All request parameters.
     *
     * @return String
     */
    public function logoutAccount(Request $request)
    {
        $currentUser = Auth::guard('user')->user();
        if ($request->session()->has('sessionToken') && $currentUser) {
            $sessionToken = $request->session()->get('sessionToken');
            //check in db
            $sessionInfo = Session::where('userid', '=', $currentUser->id)->where('session_token', '=', $sessionToken)->first();
            
            $sessionId = $sessionInfo->id;

            //save session
            $end = Carbon::now()->toDateTimeString();
            Session::where('id', $sessionId)->update(array('end' => $end, 'last_activity' => $end));

            //save log
            //@todo - check already updated
            $checkDuplicate = true;
            CustomLog::saveLog($sessionId, 'logout', $checkDuplicate);
        }

        Auth::guard('user')->logout();
        return redirect('/');
    }
}
