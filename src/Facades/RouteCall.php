<?php
namespace ErickComp\RouteCall\Facades;

use ErickComp\RouteCall\RouteCaller;
use Illuminate\Support\Facades\Facade;

class RouteCall extends Facade
{
    protected static function getFacadeAccessor()
    {
        return RouteCaller::class;
    }
}
