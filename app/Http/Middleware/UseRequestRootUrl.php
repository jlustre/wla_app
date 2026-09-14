<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class UseRequestRootUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $root = $request->getSchemeAndHttpHost();

        config(['app.url' => $root]);
        URL::forceRootUrl($root);
        URL::forceScheme($request->getScheme());

        return $next($request);
    }
}
