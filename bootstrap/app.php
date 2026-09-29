<?php

use App\Http\Middleware\EnsurePatientHasConsent;
use App\Http\Middleware\MobileApiLocale;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\ValidateSignature;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/upload-consent',
            '/upload-consent-test',
        ]);
        $middleware->alias([
            'consent' => EnsurePatientHasConsent::class,
            'signed' => ValidateSignature::class,

        ]);
         $middleware->appendToGroup('web', [
            SetLocale::class,
        ]);
        $middleware->alias([
                'api.locale' => MobileApiLocale::class,
            ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e, $request) {
            if (!$request->is('api/*')) {
                return;
            }
    
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return response()->json([
                    'message' => 'De ingevoerde gegevens zijn ongeldig.',
                    'errors'  => $e->errors(),
                ], 422);
            }
    
            if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'message' => 'Niet geautoriseerd. Log opnieuw in.',
                ], 401);
            }
    
            if ($e instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
                return response()->json([
                    'message' => 'Te veel pogingen. Probeer het later opnieuw.',
                ], 429);
            }
    
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return response()->json([
                    'message' => 'De opgevraagde pagina bestaat niet.',
                ], 404);
            }
    
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                return response()->json([
                    'message' => 'Deze methode is niet toegestaan.',
                ], 405);
            }
        });
    })->create();


