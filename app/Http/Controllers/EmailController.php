<?php

namespace App\Http\Middleware;

use Closure;
use App\Settings\GeneralSettings;

class EmailMiddleware
{
    public function handle($request, Closure $next)
    {
        $email = app(GeneralSettings::class)->email;

        // Use the $email variable as needed

        return $next($request);
    }
}
