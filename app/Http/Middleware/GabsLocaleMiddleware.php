<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use LaravelLocalization;

class GabsLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        // 1. get current locale
        $locale = LaravelLocalization::getCurrentLocale();
        
        // set carbon locale
        Carbon::setLocale($locale);

        return $next($request);
    }
}
