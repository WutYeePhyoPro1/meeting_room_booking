<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $url = url()->current();
        $id  = getAuth()->id ?? null;
        if($id && ($url == 'http://127.0.0.1:8000' || $url == 'http://127.0.0.1:8000/login')){
            return back();
        }else{
            return $next($request);
        }
    }
}
