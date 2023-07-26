<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $result = $next($request);
        $endTime = microtime(true);

        if($result instanceof JsonResponse) {
            $responseData = $result->getData(true);
            $debug_info = [];
            $debug_info['execution-time-milliseconds'] = $endTime - $startTime;
            $debug_info['requested-get-parameters'] = $request->query();
            $debug_info['requested-post-body'] = $request->post();
            $responseData['debug-info'] = $debug_info;
            $result->setData($responseData);
        }
        return $result;
    }
}
