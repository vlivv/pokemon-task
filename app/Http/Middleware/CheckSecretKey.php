<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSecretKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = $request->header('X-SUPER-SECRET-KEY');
        if (!$secret || $secret !== env('SUPER_SECRET_KEY')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
