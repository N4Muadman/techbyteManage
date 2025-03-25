<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        $segments = explode('/', $path);

        $basePath = implode('/', array_slice($segments, 0, 2));

        if(!Auth::user()->hasPermissionOnPath('1', '/' . $basePath)){
            return redirect('no-access');
        }

        return $next($request);
    }
}
