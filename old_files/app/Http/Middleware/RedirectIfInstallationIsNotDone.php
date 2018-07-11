<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfInstallationIsNotDone
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
        
        if(!\Storage::disk('installPath')->exists('DO_NOT_TOUCH/site_installed.key')){
            return redirect(route('frontend.site.install'));
        }
        
        return $next($request);
    }
}
