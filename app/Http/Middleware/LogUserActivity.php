<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $userId = auth()->id();
            $path = $request->path();
            $method = $request->method();

            Log::info("User ID {$userId} accessed {$path} via {$method}");
        }

        return $next($request);
    }
}