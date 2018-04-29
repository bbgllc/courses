<?php

namespace App\Http\Middleware;

use Closure;


class ThemeMiddleware
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
        // this theme should be fetched from the database and passed here dynamically
        $theme = 'default';
        
        \Theme::set($theme);
        return $next($request);
    }
    
}
