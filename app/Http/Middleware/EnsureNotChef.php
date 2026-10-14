<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotChef
{
    /**
     * Handle an incoming request.
     *
     * Blocks users with the 'Chef' role from administrative, financial, and driver routing features.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role === 'Chef') {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthorized. Chefs are restricted from accessing administrative and financial features.'
                ], 403);
            }

            abort(403, 'Unauthorized access. Chefs are restricted from administrative, routing, and financial operations.');
        }

        return $next($request);
    }
}
