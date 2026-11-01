<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // A 404 can come from outside the locale group (or from an unknown locale),
        // where SetLocale has not run. The error page uses localized routes, so give
        // it a language before it renders; returning nothing keeps the default 404 view.
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            $first = $request->segment(1);
            $locale = array_key_exists($first, config('site.locales')) ? $first : config('site.default_locale');

            app()->setLocale($locale);
            URL::defaults(['locale' => $locale]);
        });
    })->create();
