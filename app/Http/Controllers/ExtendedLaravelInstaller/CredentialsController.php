<?php
/**
 * Credentials Controller
 *
 * @category CredentialsController
 * @package  Controller
 */

namespace App\Http\Controllers\ExtendedLaravelInstaller;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use RachidLaasri\LaravelInstaller\Helpers\PermissionsChecker;
use Validator;
use App\Admin;
use Hash;
use DB;

/**
 * Credentials Controller
 *
 * @category Class
 * @package  CredentialsController
 */
class CredentialsController extends Controller
{

    /**
     * @var PermissionsChecker
     */
    protected $credentials;

    /**
     * @param PermissionsChecker $checker
     */
    // public function __construct(PermissionsChecker $checker)
    // {
    //     $this->credentials = $checker;
    // }

    /**
     * Function for displaying credentials page.
     *
     * @return String
     */
    public function credentials()
    {
        /*$credentials = $this->credentials->check(
            config('installer.permissions')
        );*/
        $credentials = [];

        return view('extendedlaravelinstaller.credentials', compact('credentials'));
    }

    public function saveCredentials(Request $request, Redirector $redirect)
    {
        $validator = Validator::make(
            $request->all(), [
            'admin_name' => 'required|min:3',
            'admin_email' => 'required|email|min:3',
            'admin_password' => 'required|min:3',
            ]
        );


        if ($validator->fails()) {
            $errors = $validator->errors();
            //return view('extendedlaravelinstaller.credentials', compact('errors'));
            return redirect()->back()->withInput($request->input())->with('errors', $errors); 
        }
        //@todo - create new admin
        DB::table('admin_password_resets')->truncate();
        Admin::query()->truncate();

        $input['name'] = $request->get('admin_name');
        $input['email'] = $request->get('admin_email');
        $input['password'] = bcrypt($request->get('admin_password'));
        $user = Admin::create($input);

        $message = [];
        return $redirect->route('LaravelInstaller::igadmin')
                        ->with(['message' => $message]);
    }
}
