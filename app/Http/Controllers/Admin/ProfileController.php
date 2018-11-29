<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;
use App\Library\CustomLog;
use Hash;

class ProfileController extends Controller
{
	//display change password page
    public function index()
    {
    	$data = [];
        
        //@todo - call git code
//exec('git branch'.' 2>&1', $output, $return_var);
//var_dump($output);
$output =  shell_exec("git branch 2>&1");
//var_dump($output);
$data['output'] = $output;

        $currentUser = \Auth::guard('admin')->user();
        $data['user'] = $currentUser;
    	return view('admin.profile.index', $data);
    }

    public function saveProfile(Request $request)
    {
        $this->validate($request, [
            //'old_password' => 'required',
            'name' => 'required|min:4',
            'email' => 'required|email',
        ]);

        $currentUser = \Auth::guard('admin')->user();
        $currentUser->name = $request->get('name');
        $currentUser->email = $request->get('email');
        $currentUser->save();

    	return redirect('admin/profile')->with('success', 'Your profile has been updated successfully!');
    }

    public function changePassword()
    {
        $data = [];
        return view('admin.profile.changePassword', $data);
    }

    public function savePassword(Request $request)
    {
        //@todo -validate with confirm password
        $currentUser = \Auth::guard('admin')->user();

        $validator = \Validator::make(
            $request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:4',
            ]
        );
        if ($validator->passes()) {
            if(Hash::check($request->get('old_password'), $currentUser->password)){
                $currentUser->password = bcrypt($request->get('new_password'));
                $currentUser->save();

                return redirect('admin/changePassword')->with('success', 'Your password has been changed successfully!');
            }
            return back()->withErrors(['Password does not match.']);
        }
        return back()->withErrors($validator->errors());
    }
}
