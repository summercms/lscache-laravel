<?php

namespace Litespeed\LSCache;

use Closure;

class LSTagsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $lscache_tags
     * @return mixed
     */
    public function handle($request, Closure $next, string $lscache_tags = null)
    {
        $response = $next($request);

        if (! $request->isMethodCacheable() || ! $response->getContent()) {
            return $response;
        }

        if(isset($lscache_tags)) {
            $lscache_string = str_replace(';', ',', $lscache_tags);
        }

        if($response->headers->has('X-LiteSpeed-Tag') == false) {
            $response->headers->set('X-LiteSpeed-Tag', $lscache_string);
        }

        return $response;
    }
}
