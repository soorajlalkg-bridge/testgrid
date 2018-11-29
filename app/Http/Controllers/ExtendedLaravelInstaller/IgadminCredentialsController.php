<?php
/**
 * Igadmin Controller
 *
 * @category IgadminCredentialsController
 * @package  Controller
 */

namespace App\Http\Controllers\ExtendedLaravelInstaller;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use RachidLaasri\LaravelInstaller\Helpers\PermissionsChecker;
use Validator;
use App\Igadmin;
use Hash;
use DB;

/**
 * Igadmin Controller
 *
 * @category Class
 * @package  IgadminCredentialsController
 */
class IgadminCredentialsController extends Controller
{

    /**
     * @var PermissionsChecker
     */
    protected $credentials;

    public function credentials()
    {
        $credentials = [];

        return view('extendedlaravelinstaller.igadmincredentials', compact('credentials'));
    }

    public function saveCredentials(Request $request, Redirector $redirect)
    {
        $validator = Validator::make(
            $request->all(), [
            'igadmin_email' => 'required|email|min:3',
            'igadmin_password' => 'required|confirmed|min:3',
            ]
        );


        if ($validator->fails()) {
            $errors = $validator->errors();
            //return view('extendedlaravelinstaller.igadmincredentials', compact('errors'));
            return redirect()->back()->withInput($request->input())->with('errors', $errors);
        }
        //@todo - create new igolgi admin
        Igadmin::query()->truncate();

        $input['name'] = 'Igolgi Admin';
        $input['email'] = $request->get('igadmin_email');
        $input['password'] = bcrypt($request->get('igadmin_password'));
        $user = Igadmin::create($input);

        $message = [];
        return $redirect->route('LaravelInstaller::licenses')
                        ->with(['message' => $message]);
    }
}
