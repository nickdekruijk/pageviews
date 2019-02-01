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
        Pageviews::track($request);
        $response = $next($request);
        return $response;
    }
}
