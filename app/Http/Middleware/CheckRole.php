<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
     public function handle(Request $request, Closure $next, ...$roles)
     {
         if (Auth::check() && Auth::user()->hasAnyRole($roles)) {
             return $next($request);
         }
 
         return redirect('/')->with('error', 'Acceso no autorizado');
     }
}
