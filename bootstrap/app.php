<?php

use App\Http\Middleware\UserOwnsArticle;
use App\Http\Middleware\UserOwnsComment;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registrazione alias per il middleware custom, così posso usarlo
        // nelle rotte scrivendo semplicemente 'owns.article'.
        $middleware->alias([
            'owns.article' => UserOwnsArticle::class,
            'owns.comment' => UserOwnsComment::class,

            // Alias dei middleware di spatie/laravel-permission
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
