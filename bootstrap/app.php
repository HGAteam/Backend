<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )->withProviders([
    ])
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
           'exception.handler' => App\Http\Middleware\ExceptionMiddleware::class,
        ]);
         $middleware->append([
            App\Http\Middleware\CorsMiddleware::class, // Habilita CORS en toda la API
        ]);
    })
    ->withExceptions(function ($exceptions) {

        /**
         * Esto asegurará que si un usuario
         * intenta acceder sin un token o con un token inválido,
         * reciba un error 401 con un mensaje claro.
         * */
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No autorizado. Se requiere un token válido.',
                'errors'  => [],
            ], 401);
        });

        $exceptions->render(function (\Laravel\Passport\Exceptions\OAuthServerException $e, $request) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Token inválido o expirado.',
                'errors'  => [],
            ], 401);
        });
    })->create();
