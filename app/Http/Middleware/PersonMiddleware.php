<?php

namespace App\Http\Middleware;

use Closure;

class PersonMiddleware
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
        // there are 4 types of operation, list, create, update, delete
        // maybe have different permission in future ?

        return $next($request);
    }
}
