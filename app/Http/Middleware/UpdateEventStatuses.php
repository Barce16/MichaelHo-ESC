<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateEventStatuses
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only run once per minute to avoid excessive processing
        $cacheKey = 'event_statuses_last_updated';

        if (!Cache::has($cacheKey)) {
            try {
                // Run the command
                Artisan::call('events:update-statuses');

                // Cache for 1 minute - prevents running too frequently
                Cache::put($cacheKey, true, 60);
            } catch (\Exception $e) {
                // Silently fail - don't break the site
                Log::error('Failed to update event statuses', ['error' => $e->getMessage()]);
            }
        }

        return $next($request);
    }
}
