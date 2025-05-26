<?php

use ErickComp\RouteCall\RouteCaller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as CurrentRequest;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::get('/hello', fn() => 'world')->name('hello');
    Route::post('/echo', fn(Request $request) => $request->input('message'))->name('echo');
    Route::post('/file-upload', fn(Request $request) => $request->file('doc')->getClientOriginalName())->name('upload');
    Route::get('/cookie-check', fn(Request $request) => $request->cookie('foo', 'default'))->name('cookie-check');
});

it('can call a named GET route', function () {
    $result = (new RouteCaller())->get('hello');
    expect($result->getContent())->toBe('world');
});

it('can call a named POST route with input', function () {
    $result = (new RouteCaller())->post('echo', input: ['message' => 'Hello Pest!']);
    expect($result->getContent())->toBe('Hello Pest!');
});

it('throws if files are passed to GET request', function () {
    $this->expectException(InvalidArgumentException::class);
    (new RouteCaller())->call('GET', 'hello', files: ['dummy' => 'value']);
});

it('can handle file uploads in POST', function () {
    $file = new \Illuminate\Http\UploadedFile(
        path: __FILE__,
        originalName: 'RouteCallerTest.php',
        test: true,
    );

    $result = (new RouteCaller())->post('file-upload', files: ['doc' => $file]);

    expect($result->getContent())->toBe('RouteCallerTest.php');
});

it('merges cookies using append mode', function () {
    // Set current request cookie
    request()->cookies->set('foo', 'bar');

    $result = (new RouteCaller())->get('cookie-check', cookies: ['baz' => 'qux'], cookiesMode: RouteCaller::COOKIES_MODE_APPEND);
    expect($result->getContent())->toBe('bar'); // `foo` from original request is preserved
});

it('overrides cookies using override mode', function () {
    request()->cookies->set('foo', 'bar');

    $result = (new RouteCaller())->get('cookie-check', cookies: ['foo' => 'baz'], cookiesMode: RouteCaller::COOKIES_MODE_OVERRIDE);
    expect($result->getContent())->toBe('baz');
});

it('throws on invalid cookie mode', function () {
    request()->cookies->set('foo', 'bar');

    expect(fn() => (new RouteCaller())->get('cookie-check', cookies: ['foo' => 'baz'], cookiesMode: 'invalid'))
        ->toThrow(InvalidArgumentException::class);
});

it('calls a route using the URI instead of the name', function () {
    $handler = function (string $param) {
        return "URI: $param";
    };

    Route::get('/custom-uri/{param}', $handler);

    $response = (new RouteCaller())->get('/custom-uri/foo');

    expect($response->getContent())->toBe('URI: foo');
});

it('throws when cookies are passed without a mode', function () {
    Route::post('/test', fn() => 'ok');

    (new RouteCaller())->post('/test', input: [], cookies: ['foo' => 'bar']);
})->throws(InvalidArgumentException::class, 'you must provide the $cookiesMode');

it('overrides cookies when using override mode', function () {
    Route::get('/cookie-check', fn(Request $req) => $req->cookie('override'));

    $caller = new RouteCaller();
    $response = $caller->get('/cookie-check', cookies: ['override' => '123'], cookiesMode: RouteCaller::COOKIES_MODE_OVERRIDE);

    expect($response->getContent())->toBe('123');
});

it('merges cookies when using append mode', function () {
    Route::get('/cookie-merge', fn(Request $req) => $req->cookie('merged'));

    CurrentRequest::getFacadeRoot()->cookies->set('merged', 'original');

    $caller = new RouteCaller();
    $response = $caller->get('/cookie-merge', cookies: ['merged' => 'new'], cookiesMode: RouteCaller::COOKIES_MODE_APPEND);

    expect($response->getContent())->toBe('new');
});


it('returns a full Response object with status and headers', function () {
    Route::get('/full-response', fn() => response('Done', 202)->header('X-Test', 'OK'));

    $response = (new RouteCaller())->get('/full-response');

    expect($response)
        ->getStatusCode()->toBe(202)
        ->and($response->headers->get('X-Test'))->toBe('OK')
        ->and($response->getContent())->toBe('Done');
});

it('runs middleware when $shouldSkipMiddleware is false, regardless of container binding', function () {
    app()->instance('middleware.disable', true); // Would normally skip middleware

    include_once __DIR__ . '/TestMiddleware.php';

    Route::middleware([TestMiddleware::class])->get('/middleware-force-run', fn() => 'ok');

    $response = (new RouteCaller())->get('/middleware-force-run', shouldSkipMiddleware: false);

    expect($response->getContent())->toBe('blocked by middleware');
});

it('skips middleware when $shouldSkipMiddleware is true, regardless of container binding', function () {
    app()->instance('middleware.disable', false); // Would normally allow middleware

    include_once __DIR__ . '/TestMiddleware.php';

    Route::middleware([TestMiddleware::class])->get('/middleware-force-skip', fn() => 'ok');

    $response = (new RouteCaller())->get('/middleware-force-skip', shouldSkipMiddleware: true);

    expect($response->getContent())->toBe('ok');
});

it('uses container binding when $shouldSkipMiddleware is null', function () {
    app()->instance('middleware.disable', true);

    include_once __DIR__ . '/TestMiddleware.php';

    Route::middleware([TestMiddleware::class])->get('/middleware-null-skip', fn() => 'ok');

    $response = (new RouteCaller())->get('/middleware-null-skip');

    expect($response->getContent())->toBe('ok');
});

it('runs middleware when $shouldSkipMiddleware is null and container has no binding', function () {
    if (app()->bound('middleware.disable')) {
        app()->forgetInstance('middleware.disable');
    }

    include_once __DIR__ . '/TestMiddleware.php';

    Route::middleware([TestMiddleware::class])->get('/middleware-null-default', fn() => 'ok');

    $response = (new RouteCaller())->get('/middleware-null-default');

    expect($response->getContent())->toBe('blocked by middleware');
});
