<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Log;
use Str;
use Symfony\Component\HttpFoundation\Response;

class HttpTrafficLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // - start time -
        $startTime = microtime(true);

        // --- create a request ID ---
        $requestId = (string) Str::uuid();

        // --- share the context for all logs ---
        Context::add('request-id', $requestId);

        // --- log request data ---
        // - adding data to context -
        Context::addHidden([
            'request-ip-address' => $request->ip(),
            'request-method' => $request->method(),
            'request-endpoint' => $request->fullUrl(),
        ]);
        // -- log the data --
        Log::stack(['single', 'requestLog', 'devLog'])
            ->debug(
                'incoming request',
                Context::onlyHidden([
                    'request-ip-address',
                    'request-method',
                    'request-endpoint'
                ])
            );

        // --- log response data ---
        // -- passing middleware --
        $response = $next($request);
        // -- response data to be logged --
        // - end time -
        $endTime = microtime(true);
        // - calculate duration -
        $responseTime = $endTime - $startTime;
        // - adding data to context
        Context::addHidden([
            'status-code' => $response->getStatusCode(),
            'duration' => $responseTime,
        ]);
        // -- log response data --
        Log::stack(['single', 'responseLog', 'devLog'])
            ->debug(
                'outgoing response',
                Context::onlyHidden([
                    'status-code',
                    'duration'
                ])
            );

        return $response;
    }
}
