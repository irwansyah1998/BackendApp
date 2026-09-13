<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $expectedKey = env('API_KEY');

        if (blank($expectedKey)) {
            return response()->json([
                'message' => 'API key is not configured on the server.',
            ], 500);
        }

        $providedKey = $request->header('X-API-KEY')
            ?? $request->header('X-Api-Key')
            ?? $request->query('api_key')
            ?? $request->bearerToken();

        if (! is_string($providedKey) || ! hash_equals((string) $expectedKey, $providedKey)) {
            return response()->json([
                'message' => 'Unauthorized: invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
