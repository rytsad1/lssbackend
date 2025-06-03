<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
            'json' => \App\Http\Middleware\JsonFormat::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'message' => "Duomenys nerasti.",
                    ], 404);
                } elseif ($e instanceof BadRequestException) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], 400);
                } elseif ($e instanceof AuthenticationException) {
                    return response()->json([
                        'message' => "Vartotojas neprisijungęs!",
                    ], 401);
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    return response()->json([
                        'message' => "HTTP metodas neleistinas!",
                    ], 405);
                } elseif ($e instanceof HttpException && $e->getStatusCode() == 403) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], 403);
                } elseif ($e instanceof TooManyRequestsHttpException) {
                    return response()->json([
                        'message' => "Nusiųsta per daug užklausų į serverį, prašome palaukti.",
                    ], 429);
                }
            } else {
                return response()->json([
                    'message' => "Duomenys nerasti.",
                ], 404);
            }
        });
    })
    ->create();
