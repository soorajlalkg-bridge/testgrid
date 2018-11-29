<?php
/**
 * Licenses Controller
 *
 * @category LicensesController
 * @package  Controller
 */

namespace App\Http\Controllers\ExtendedLaravelInstaller;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use RachidLaasri\LaravelInstaller\Helpers\PermissionsChecker;
use Validator;
use App\Library\Encrypt;

/**
 * Licenses Controller
 *
 * @category Class
 * @package  LicensesController
 */
class LicensesController extends Controller
{

    /**
     * @var PermissionsChecker
     */
    protected $licenses;

    /**
     * @param PermissionsChecker $checker
     */
    // public function __construct(PermissionsChecker $checker)
    // {
    //     $this->credentials = $checker;
    // }

    /**
     * Function for displaying home page.
     *
     * @return String
     */
    public function licenses()
    {
        $licenses = [];

        return view('extendedlaravelinstaller.licenses', compact('licenses'));
    }

    public function saveLicenses(Request $request, Redirector $redirect)
    {
        $validator = Validator::make(
            $request->all(), [
            'user_licenses' => 'required|integer',
            'encryption_key' => 'required|alpha_dash|min:4|max:30',
            ]
        );


        if ($validator->fails()) {
            $errors = $validator->errors();
            //return view('extendedlaravelinstaller.licenses', compact('errors'));
            return redirect()->back()->withInput($request->input())->with('errors', $errors);
        }
        //@todo - create encrypted file
        $ul = $request->get('user_licenses');
        $encryptionKey = $request->get('encryption_key');
        //$encUAL = Encrypt::encrypt($ul);
        $encUAL = Encrypt::encryptIt($ul, $encryptionKey);
        \Storage::disk('local')->put('.ual', $encUAL);

        $message = [];
        return $redirect->route('LaravelInstaller::final')
                        ->with(['message' => $message]);
    }
}
