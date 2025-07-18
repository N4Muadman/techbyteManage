<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $pageId = null, ...$permissionIds): Response
    {
        if (!is_array($permissionIds)) {
            $permissionIds = $permissionIds ? explode(',', $permissionIds) : [];
        }

        if (!Auth::user()->canAccessPage($permissionIds, $pageId)) {
            return redirect('/no-access');
        }

        return $next($request);
    }
}
