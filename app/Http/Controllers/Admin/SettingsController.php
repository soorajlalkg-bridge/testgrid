<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Admin;

class SettingsController extends Controller
{
	//display change password page
    public function index()
    {
    	$data = [];
    	return view('admin.settings.index', $data);
    }

    public function saveSettings(Request $request)
    {
        //@todo -validate with confirm password
        $this->validate($request, [
            //'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();

    	return redirect('admin/settings')->with('message', 'Your password has been changed successfully!');
    }

    public function upgrade()
    {
        $data = [];
        $output =  shell_exec("git fetch --dry-run 2>&1");
    //var_dump($output);
    $data['output'] = $output;
    	return view('admin.settings.upgrade', $data);
    }

    public function upgradeVersion(Request $request)
    {
        $output =  shell_exec("git fetch --dry-run 2>&1");
        $message = 'Version has been upgraded successfully!';
        if ($output == NULL) {
            $message = 'Same version';
        } else {
            shell_exec("git pull origin master");
        }

    	return redirect('admin/upgrade')->with('success', $message);
    }
}
