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
            $responseData['debug-info'] = [
                'execution-time-milliseconds' => $endTime - $startTime,
                'requested-get-parameters' => $request->query(),
                'requested-post-body' => $request->post(),
            ];
            $result->setContent(json_encode($responseData));
        }
        return $result;
    }
}
