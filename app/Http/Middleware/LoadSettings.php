<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LoadSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company_id = session('company_id');

        if (empty($company_id)) {
            return $next($request);
        }

        loadSettings();

        return $next($request);
    }
}
