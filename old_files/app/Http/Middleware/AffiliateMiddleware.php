<?php

namespace App\Http\Middleware;

use Closure;

class AffiliateMiddleware
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
        
        // check if no cookie is attached to the incoming request and if it has a ref id, 
        // set the cookie before moving on.
        // if( !$request->hasCookie('tutorpro_affid') && $request->query('ref') ) {
        /*
        if( $request->query('ref') ) {
            
            return redirect($request->fullUrl())
                    ->withCookie( cookie('educore_aff', $request->query('ref'), 
                                  config('site_settings.earning_affiliate_cookie_lifetime')));
        }*/
        
        $response = $next($request);
        
        if ($request->query('ref')) {
            return $response->withCookie(cookie('EDUCORE_AFFID', $request->ref, config('site_settings.earning_affiliate_cookie_lifetime')));
        }
        
        return $next($request);
    }
}
