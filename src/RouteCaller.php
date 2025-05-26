<?php

namespace ErickComp\RouteCall;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as CurrentRequest;
use Illuminate\Routing\Pipeline;
use Illuminate\Container\Container;
use Illuminate\Routing\Route as RoutingRoute;

class RouteCaller
{
    public const COOKIES_MODE_APPEND = 'append';
    public const COOKIES_MODE_OVERRIDE = 'override';

    public const COOKIES_MODES = [
        self::COOKIES_MODE_APPEND,
        self::COOKIES_MODE_OVERRIDE,
    ];

    /**
     * Calls a GET route with the specified data.
     * 
     * @param string $method The HTTP method
     * @param string $target The route to be called. This can be the route name or URI. If a route name matches a URI, the name takes precedence.
     * @param array $urlParams URL parameters for the route. If using a URI, the parameters can also be passed directly within it.
     * @param array $input The input (body) of the request
     * @param array $files The input files for the request
     * @param array|null $cookies Cookies to use in the request. These can override or be merged with the current request cookies depending on $cookiesMode.
     * @param string|null $cookiesMode This parameter determines whether the provided cookies should override the current request's cookies or be appended to them.
     * @param  bool|null  $shouldSkipMiddleware  Whether to skip middleware for this request.
     *                                           - If `true`, all route middleware will be skipped.
     *                                           - If `false`, all middleware will run, regardless of any container bindings.
     *                                           - If `null`, the behavior follows Laravel's default logic using the container binding 'middleware.disable'.
     * 
     * @throws \InvalidArgumentException
     */
    public function get(
        string $target,
        array $urlParams = [],
        array $input = [],
        ?array $cookies = null,
        ?string $cookiesMode = null,
        ?bool $shouldSkipMiddleware = null,
    ) {
        return $this->call(
            'GET',
            $target,
            $urlParams,
            $input,
            cookies: $cookies,
            cookiesMode: $cookiesMode,
            shouldSkipMiddleware: $shouldSkipMiddleware,
        );
    }

    /**
     * Calls a POST route with the specified data.
     * 
     * @param string $target The route to be called. This can be the route name or URI. If a route name matches a URI, the name takes precedence.
     * @param array $urlParams URL parameters for the route. If using a URI, the parameters can also be passed directly within it.
     * @param array $input The input (body) of the request
     * @param array $files The input files for the request
     * @param array|null $cookies Cookies to use in the request. These can override or be merged with the current request cookies depending on $cookiesMode.
     * @param string|null $cookiesMode This parameter determines whether the provided cookies should override the current request's cookies or be appended to them.
     * @param  bool|null  $shouldSkipMiddleware  Whether to skip middleware for this request.
     *                                           - If `true`, all route middleware will be skipped.
     *                                           - If `false`, all middleware will run, regardless of any container bindings.
     *                                           - If `null`, the behavior follows Laravel's default logic using the container binding 'middleware.disable'.
     * 
     * @throws \InvalidArgumentException
     */

    public function post(
        string $target,
        array $urlParams = [],
        array $input = [],
        array $files = [],
        ?array $cookies = null,
        ?string $cookiesMode = null,
        ?bool $shouldSkipMiddleware = null,
    ) {
        return $this->call(
            'POST',
            $target,
            $urlParams,
            $input,
            $files,
            $cookies,
            $cookiesMode,
            $shouldSkipMiddleware,
        );
    }

    /**
     * Calls a PUT route with the specified data.
     * 
     * @param string $target The route to be called. This can be the route name or URI. If a route name matches a URI, the name takes precedence.
     * @param array $urlParams URL parameters for the route. If using a URI, the parameters can also be passed directly within it.
     * @param array $input The input (body) of the request
     * @param array $files The input files for the request
     * @param array|null $cookies Cookies to use in the request. These can override or be merged with the current request cookies depending on $cookiesMode.
     * @param string|null $cookiesMode This parameter determines whether the provided cookies should override the current request's cookies or be appended to them.
     * @param  bool|null  $shouldSkipMiddleware  Whether to skip middleware for this request.
     *                                           - If `true`, all route middleware will be skipped.
     *                                           - If `false`, all middleware will run, regardless of any container bindings.
     *                                           - If `null`, the behavior follows Laravel's default logic using the container binding 'middleware.disable'.
     * 
     * @throws \InvalidArgumentException
     */
    public function put(
        string $target,
        array $urlParams,
        array $input = [],
        array $files = [],
        ?array $cookies = null,
        ?string $cookiesMode = null,
        ?bool $shouldSkipMiddleware = null,
    ) {
        return $this->call(
            'PUT',
            $target,
            $urlParams,
            $input,
            $files,
            $cookies,
            $cookiesMode,
            $shouldSkipMiddleware,
        );
    }

    /**
     * Calls a PATCH route with the specified data.
     * 
     * @param string $target The route to be called. This can be the route name or URI. If a route name matches a URI, the name takes precedence.
     * @param array $urlParams URL parameters for the route. If using a URI, the parameters can also be passed directly within it.
     * @param array $input The input (body) of the request
     * @param array $files The input files for the request
     * @param array|null $cookies Cookies to use in the request. These can override or be merged with the current request cookies depending on $cookiesMode.
     * @param string|null $cookiesMode This parameter determines whether the provided cookies should override the current request's cookies or be appended to them.
     * @param  bool|null  $shouldSkipMiddleware  Whether to skip middleware for this request.
     *                                           - If `true`, all route middleware will be skipped.
     *                                           - If `false`, all middleware will run, regardless of any container bindings.
     *                                           - If `null`, the behavior follows Laravel's default logic using the container binding 'middleware.disable'.
     * 
     * @throws \InvalidArgumentException
     */
    public function patch(
        string $target,
        array $urlParams,
        array $input = [],
        array $files = [],
        ?array $cookies = null,
        ?string $cookiesMode = null,
        ?bool $shouldSkipMiddleware = null,
    ) {
        return $this->call(
            'PATCH',
            $target,
            $urlParams,
            $input,
            $files,
            $cookies,
            $cookiesMode,
            $shouldSkipMiddleware,
        );
    }

    /**
     * Calls a DELETE route with the specified data.
     * 
     * @param string $target The route to be called. This can be the route name or URI. If a route name matches a URI, the name takes precedence.
     * @param array $urlParams URL parameters for the route. If using a URI, the parameters can also be passed directly within it.
     * @param array $input The input (body) of the request
     * @param array|null $cookies Cookies to use in the request. These can override or be merged with the current request cookies depending on $cookiesMode.
     * @param string|null $cookiesMode This parameter determines whether the provided cookies should override the current request's cookies or be appended to them.
     * @param  bool|null  $shouldSkipMiddleware  Whether to skip middleware for this request.
     *                                           - If `true`, all route middleware will be skipped.
     *                                           - If `false`, all middleware will run, regardless of any container bindings.
     *                                           - If `null`, the behavior follows Laravel's default logic using the container binding 'middleware.disable'.
     * 
     * @throws \InvalidArgumentException
     */
    public function delete(
        string $target,
        array $urlParams,
        array $input = [],
        ?array $cookies = null,
        ?string $cookiesMode = null,
        ?bool $shouldSkipMiddleware = null,
    ) {
        return $this->call(
            'DELETE',
            $target,
            $urlParams,
            $input,
            cookies: $cookies,
            cookiesMode: $cookiesMode,
            shouldSkipMiddleware: $shouldSkipMiddleware,
        );
    }

    /**
     * Calls a route with the specified data.
     * 
     * @param string $method The HTTP method
     * @param string $target The route to be called. This can be the route name or URI. If a route name matches a URI, the name takes precedence.
     * @param array $urlParams URL parameters for the route. If using a URI, the parameters can also be passed directly within it.
     * @param array $input The input (body) of the request
     * @param array $files The input files for the request
     * @param array|null $cookies Cookies to use in the request. These can override or be merged with the current request cookies depending on $cookiesMode.
     * @param string|null $cookiesMode This parameter determines whether the provided cookies should override the current request's cookies or be appended to them.
     * @param  bool|null  $shouldSkipMiddleware  Whether to skip middleware for this request.
     *                                           - If `true`, all route middleware will be skipped.
     *                                           - If `false`, all middleware will run, regardless of any container bindings.
     *                                           - If `null`, the behavior follows Laravel's default logic using the container binding 'middleware.disable'.
     * 
     * @throws \InvalidArgumentException
     */
    public function call(
        string $method,
        string $target,
        array $urlParams = [],
        array $input = [],
        array $files = [],
        ?array $cookies = null,
        ?string $cookiesMode = null,
        ?bool $shouldSkipMiddleware = null,
    ) {

        /** @var Request */
        $currentRequest = CurrentRequest::getFacadeRoot();

        if (!in_array(\strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($files)) {
            throw new \InvalidArgumentException("Files can only be uploaded using POST, PUT, or PATCH methods.");
        }

        $requestCookies = $cookies === null
            ? $currentRequest->cookies->all()
            : match ($cookiesMode) {
                static::COOKIES_MODE_APPEND => [...$currentRequest->cookies->all(), ...$cookies],
                static::COOKIES_MODE_OVERRIDE => $cookies,
                default => throw new \InvalidArgumentException(
                    'When you provide cookies, you must provide the $cookiesMode with one of the following values [' . \implode(', ', static::COOKIES_MODES) . ']'
                )
            };

        /** @var Illuminate\Routing\Router */
        $router = Route::getFacadeRoot();

        // Try to find route by name
        /** @var Illuminate\Routing\Route|null $route*/
        if ($route = $router->getRoutes()->getByName($target)) {
            if (!\in_array(\strtoupper($method), $route->methods())) {
                $route = null;
            }
        }

        $routeUri = $route?->uri() ?? $target;

        if ($currentRequest->hasSession() && \in_array(\strtoupper($method), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $input['_token'] = $currentRequest->session()->token();
        }

        $manualRequest = Request::create(
            $routeUri,
            $method,
            $input,
            $requestCookies,
            $files,
            $currentRequest->server->all(),
        );

        $manualRequest->setUserResolver($currentRequest->getUserResolver());

        if ($currentRequest->hasSession()) {
            $manualRequest->setLaravelSession($currentRequest->session());
        }

        $container = app();

        if (!$route) {
            $route = $router->getRoutes()->match($manualRequest);
        }

        $route = clone $route;

        $route->setContainer($container);
        $route->bind($manualRequest);
        $this->bindUrlParamsToRoute($route, $urlParams);


        $manualRequest->setRouteResolver(fn() => $route);

        if ($shouldSkipMiddleware === null) {
            $shouldSkipMiddleware = $container->bound('middleware.disable') &&
                $container->make('middleware.disable') === true;
        }

        $middleware = $shouldSkipMiddleware ? [] : Route::gatherRouteMiddleware($route);

        $callAction = function () use ($container, $middleware, $manualRequest, $router, $route) {
            try {
                return (new Pipeline($container))
                    ->send($manualRequest)
                    ->through($middleware)
                    ->then(fn($req) => $router->prepareResponse($req, $route->run()));
            } catch (\Throwable $t) {
                \xdebug_break();
                return $router->prepareResponse($manualRequest, $t);
            }

        };

        return $this->withTemporaryRequestBinding($container, $manualRequest, $callAction);
    }

    protected function bindUrlParamsToRoute(RoutingRoute $route, array $urlParams)
    {
        static $routeParamsSetter = function (array $urlParams) {
            $this->originalParameters = \array_merge($this->originalParameters, $urlParams);
            $this->parameters = \array_merge($this->parameters, $urlParams);
        };

        $routeParamsSetter->call($route, $urlParams);
    }

    protected function withTemporaryRequestBinding(Container $container, Request $tempRequest, callable $callback)
    {
        $hasOriginal = $container->bound('request');
        $originalRequest = $hasOriginal ? $container->make('request') : null;

        try {
            $container->instance('request', $tempRequest);
            return $callback();
        } finally {
            if ($hasOriginal) {
                $container->instance('request', $originalRequest);
            } else {
                $container->forgetInstance('request');
            }
        }
    }
}
