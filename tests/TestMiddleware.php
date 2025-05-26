<?php

use Illuminate\Http\Request;

class TestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return response('blocked by middleware');
    }
}
