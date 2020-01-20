<?php

namespace EvolutionCMS\Routing;

use Closure;
use Event;

class CancelParserMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        Event::listen(['evolution.OnWebPageInit', 'evolution.OnPageNotFound'], function() {
            exit;
        });

        return $next($request);
    }
}
