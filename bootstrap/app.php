<?php

use PhpParser\Node\Expr\AssignOp\Mod;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Request;  // Make sure you're using the correct Request class

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (MethodNotAllowedHttpException $e, Request $request) {
            // Check if the request is for /api/login and method is GET
            if ($request->is('api/login') && $request->isMethod('get')) {
                return response()->json([
                    'status' => 401,
                    'message' => 'You need to login first',
                ], 401);
            }

            // For other method not allowed cases, return 405
            return response()->json([
                'status' => 405,
                'message' => 'Method not allowed',
            ], 405);
        });
    })->create();
