<?php
/**
 * Final Controller
 *
 * @category FinalController
 * @package  Controller
 */

namespace App\Http\Controllers\ExtendedLaravelInstaller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
use RachidLaasri\LaravelInstaller\Helpers\FinalInstallManager;
use RachidLaasri\LaravelInstaller\Helpers\InstalledFileManager;
use RachidLaasri\LaravelInstaller\Events\LaravelInstallerFinished;

/**
 * Final Controller
 *
 * @category Class
 * @package  FinalController
 */
class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        //@todo - check db migration, admin login and user license
        //return redirect()->route('LaravelInstaller::welcome');

        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();
        
        event(new LaravelInstallerFinished);

        return view('extendedlaravelinstaller.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
