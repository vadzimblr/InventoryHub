<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);
        $middleware->statefulApi();
    })

    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'message' => 'Validation error',
                        'errors' => $e->errors()
                    ], 422);
                }

                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'message' => 'Resource not found'
                    ], 404);
                }

                return response()->json([
                    'message' => 'Server error',
                    'error' => $e->getMessage()
                ], 500);
            }
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
        );
    })->create();
