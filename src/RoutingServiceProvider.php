<?php

namespace EvolutionCMS\Routing;

use EvolutionCMS\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RoutingServiceProvider as IlluminateRoutingServiceProvider;

class RoutingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $isApiMode = defined('MODX_API_MODE') && !empty(MODX_API_MODE);
        $isCliMode = defined('MOD_CLI') && !empty(MODX_CLI);
        $isManager = defined('IN_MANAGER_MODE') && !empty(IN_MANAGER_MODE);

        if (!$isApiMode && !$isCliMode && !$isManager && $this->app->checkSiteStatus()) {
            with(new IlluminateRoutingServiceProvider($this->app))->register();

            $this->app->booted(function() {
                if (is_readable(EVO_CORE_PATH . 'custom/routes.php')) {
                    Route::middleware(CancelParserMiddleware::class)->group(function() {
                        include EVO_CORE_PATH . 'custom/routes.php';
                    });
                }

                Route::fallback(function() {
                    register_shutdown_function(function() {
                        \EvolutionCMS()->executeParser();
                    });
                });

                $request  = Request::createFromGlobals();
                $this->app->instance('request', $request);
                $response = $this->app['router']->dispatch($request);
                $response->send();
            });
        }
    }
}
