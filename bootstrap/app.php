<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\RouteNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'check-permissions'])
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));

            Route::middleware('auth:sanctum')
                ->prefix('api/admin')
                ->group(base_path('routes/admin_api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check-permissions' => \App\Http\Middleware\CheckPermissions::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return match (true) {
                    $e instanceof AuthenticationException => response()->json([
                        'success' => false,
                        'message' => 'Unauthorized Request.'
                    ], Response::HTTP_UNAUTHORIZED),

                    $e instanceof ValidationException => response()->json([
                        'message' => 'Validation failed',
                        'errors' => $e->errors(),
                    ], Response::HTTP_UNPROCESSABLE_ENTITY),

                    $e instanceof HttpException => response()->json([
                        'message' => $e->getMessage(),
                    ], $e->getStatusCode()),

                    $e instanceof RouteNotFoundException && str_contains($e->getMessage(), "login") => response()->json([
                        'success' => false,
                        'message' => 'Unauthorized Request.'
                    ], Response::HTTP_UNAUTHORIZED),

                    $e instanceof RouteNotFoundException => response()->json([
                        'message' => 'Route not found.'
                    ], Response::HTTP_NOT_FOUND),

                    default => response()->json([
                        'message' => 'An unexpected error occurred.',
                        'details' => $e->getMessage(),
                    ], Response::HTTP_INTERNAL_SERVER_ERROR),
                };
            }
        });
    })->create();
