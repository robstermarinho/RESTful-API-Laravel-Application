<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $headerName = 'X-Name')
    {
        // We are actign after obtain the response
        // adding a sgnature in headers 
        // For example: X-Name = Laravel
        $response = $next($request);
        $response->headers->set($headerName, config('app.name'));
        return $response;
    }
}
