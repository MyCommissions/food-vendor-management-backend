<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('AdminOnly middleware running', [
            'user' => $request->user(),
            'role_id' => $request->user() ? $request->user()->role_id : null
        ]);

        if (!$request->user() || $request->user()->role_id !== 3) {
            return response()->json([
                'message' => 'Access denied. Admin privileges required.',
                'user_role' => $request->user() ? $request->user()->role_id : null
            ], 403);
        }

        return $next($request);
    }
}
