<?php
/**
 * Database Controller
 *
 * @category DatabaseController
 * @package  Controller
 */


namespace App\Http\Controllers\ExtendedLaravelInstaller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RachidLaasri\LaravelInstaller\Helpers\DatabaseManager;

/**
 * Database Controller
 *
 * @category Class
 * @package  DatabaseController
 */
class DatabaseController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     */
    public function database()
    {
        $response = $this->databaseManager->migrateAndSeed();

        return redirect()->route('LaravelInstaller::credentials')
                         ->with(['message' => $response]);
    }
}
