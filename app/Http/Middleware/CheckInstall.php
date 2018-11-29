<?php

namespace App\Http\Middleware;

use Closure;

class CheckInstall
{
    public function handle($request, Closure $next)
    {
        // Perform action
    	//check installed
    	$installed = is_file(storage_path('installed'));
    	//get encrypted file
        $licensed = \Storage::disk('local')->exists('.ual');
        if (!$installed || !$licensed) {
        	@unlink(resource_path('views/vendor/installer/layouts/master.blade-old.php'));

        	rename(resource_path('views/vendor/installer/layouts/master.blade.php'), resource_path('views/vendor/installer/layouts/master.blade-old.php'));

        	copy(resource_path('views/extendedlaravelinstaller/layouts/master.blade.php'), resource_path('views/vendor/installer/layouts/master.blade.php'));

            return redirect('/install');
        }

        return $next($request);
    }
}