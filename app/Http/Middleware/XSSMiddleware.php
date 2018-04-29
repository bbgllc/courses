<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class XSSMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $exempt = array('body', 'description', 'biography', 'article_body');
        
        $input = $request->except($exempt);
        
        array_walk_recursive($input, function (&$input) {
            $input = strip_tags($input);
        });
        
        $request->merge($input);
        
        return $next($request);
    }
}
