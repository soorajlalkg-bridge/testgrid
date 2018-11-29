<?php
/**
 * Environment Controller
 *
 * @category EnvironmentController
 * @package  Controller
 */

namespace App\Http\Controllers\ExtendedLaravelInstaller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
use RachidLaasri\LaravelInstaller\Events\EnvironmentSaved;
use Validator;
use Illuminate\Validation\Rule;

/**
 * Environment Controller
 *
 * @category Class
 * @package  EnvironmentController
 */
class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentWizard()
    {
        $envConfig = $this->EnvironmentManager->getEnvContent();

        return view('extendedlaravelinstaller.environment-wizard', compact('envConfig'));
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param Request $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWizard(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $rules['database_password'] = 'max:50';

        $messages = [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $envConfig = $this->EnvironmentManager->getEnvContent();
            //return view('vendor.installer.environment-wizard', compact('errors', 'envConfig'));
            return redirect()->back()->withInput($request->input())->with('errors', $errors)->with('envConfig', $envConfig); 
        }

        $results = $this->EnvironmentManager->saveFileWizard($request);

        event(new EnvironmentSaved($request));

        return $redirect->route('LaravelInstaller::database')
                        ->with(['results' => $results]);
    }
}
