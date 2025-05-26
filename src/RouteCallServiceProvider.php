<?php

namespace ErickComp\RouteCall;

use Illuminate\Support\ServiceProvider;

class RouteCallServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(RouteCaller::class);
    }
}
