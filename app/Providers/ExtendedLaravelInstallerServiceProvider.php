<?php

namespace App\Providers;

//use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use RachidLaasri\LaravelInstaller\Middleware\canInstall;
use RachidLaasri\LaravelInstaller\Middleware\canUpdate;
use RachidLaasri\LaravelInstaller\Providers\LaravelInstallerServiceProvider;

class ExtendedLaravelInstallerServiceProvider extends LaravelInstallerServiceProvider
{
    protected $defer = false;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        //parent::boot();
        $router->middlewareGroup('install',[CanInstall::class]);
        $router->middlewareGroup('update',[CanUpdate::class]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //parent::register();
        $this->publishFiles();
        //$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    protected function publishFiles()
    {
        // $this->publishes([
        //     __DIR__.'/../Config/installer.php' => base_path('config/installer.php'),
        // ], 'laravelinstaller');

        // $this->publishes([
        //     __DIR__.'/../assets' => public_path('installer'),
        // ], 'laravelinstaller');

        // $this->publishes([
        //     __DIR__.'/../Views' => base_path('resources/views/vendor/installer'),
        // ], 'laravelinstaller');
        
        // $this->publishes([
        //     __DIR__.'/../Lang' => base_path('resources/lang'),
        // ], 'laravelinstaller');
    }
}
