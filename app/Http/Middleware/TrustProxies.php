<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        // Strip any empty forwarded headers to prevent Symfony Request from throwing "Uninitialized string offset 0"
        foreach (['x-forwarded-host', 'x-forwarded-port', 'x-forwarded-proto', 'x-forwarded-for'] as $header) {
            if ($request->headers->has($header) && trim((string) $request->headers->get($header)) === '') {
                $request->headers->remove($header);
            }
        }

        return parent::handle($request, $next);
    }
}
