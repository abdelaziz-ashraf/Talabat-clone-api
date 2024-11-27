<?php

use App\Http\Responses\ErrorResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            return ErrorResponse::send('Validation Exception', $e->validator->errors()->all(), 401);
        });

        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
            return ErrorResponse::send('إتأكد من الميثود يا بييييه', ['Method Not Allowed Http Exception'], statusCode: 401);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return ErrorResponse::send('Record not found', ['Not Found Http Exception'], statusCode: 404);
            }
        });

        $exceptions->renderable(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return ErrorResponse::send('unAuthenticated', ['Authentication Exception'], statusCode: 401);
            }
        });

    })->create();
