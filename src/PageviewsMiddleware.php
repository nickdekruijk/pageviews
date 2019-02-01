<?php

namespace NickDeKruijk\Pageviews;

use Closure;

class PageviewsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        Pageviews::track($request);
        return $response;
    }
}
