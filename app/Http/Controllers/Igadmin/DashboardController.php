<?php

namespace App\Http\Controllers\Igadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Library\Encrypt;

class DashboardController extends Controller
{

	//display change password page
    public function index(Request $request)
    {
    	$data = [];
        $data['userLicenseLimit'] = $this->getUserLicenseLimit();
    	return view('igadmin.dashboard.index', $data);
    }

    public function saveSettings(Request $request)
    {
        $this->validate($request, [
            'user_licenses' => 'required|numeric|min:1',
            'encryption_key' => 'required|alpha_dash',
        ]);

        //update file
        $ul = $request->get('user_licenses');
        $encryptionKey = $request->get('encryption_key');
        //$encUAL = Encrypt::encrypt($ul);
        $oldEncUAL = $this->getUserLicenseLimit(false);
        if (Encrypt::validateIt($oldEncUAL, $encryptionKey)==true) {
            $encUAL = Encrypt::encryptIt($ul, $encryptionKey);
            \Storage::disk('local')->put('.ual', $encUAL);

        	return redirect('igadmin/dashboard')->with('success', 'User license has been updated successfully!');
        }
        return redirect()->back()->withErrors(['encryption_key'=> 'Encryption key is incorrect.']);
    }

    /**
     * get user license limit.
     *
     * @return Bool
     */
    private function getUserLicenseLimit($decrypt=true)
    {
        //get user active license
        $licenseExists = \Storage::disk('local')->exists('.ual');
        if (!$licenseExists) {
            return config('constants.options.ual');
        }
        $ul = \Storage::get('.ual');
        if ($decrypt == false) {
            return $ul;
        }
        //$decUAL = Encrypt::decrypt($ul);
        $decUAL = Encrypt::decryptIt($ul);
        $ual = (int) $decUAL;
        return $ual;
    }
}
