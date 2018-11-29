<?php

namespace App\Library;

use App\Action;
use App\UserLog;
use Carbon\Carbon;
use App\Session;

class CustomLog {
    public static function saveLog($sessionId='', $action, $checkDuplicate=false){
    	$now = Carbon::now();
        $timestamp = $now;//->format('H:i:s');
        $logExists = false;

        if (empty($sessionId)) {
            //get session id
            $sessionToken = \Session::get('sessionToken');
            $sessionInfo = Session::where('session_token', '=', $sessionToken)
                    ->first();
            $sessionId = $sessionInfo->id;
        }

        $action = Action::where('action_type', '=', $action)
            		->first();
        if ($action && $sessionId) {
            if ($checkDuplicate) {
                $logExists = UserLog::where('sessionid', $sessionId)
                       ->where('action', $action->id)
                       ->count();
            }
            
            if ($logExists == false) {
    	    	$input['sessionid'] = $sessionId;
    	    	$input['timestamp'] = $timestamp;
    	        $input['action'] = $action->id;
    	        $input['result'] = 1;
    	        $log = UserLog::create($input);
            }
    	}
    }
}
