<?php

// Manual autoloader for App and Database namespaces
spl_autoload_register(function ($class) {
    // Handle App namespace
    $app_prefix = 'App\\';
    $app_base_dir = __DIR__ . '/../app/';
    
    $app_len = strlen($app_prefix);
    if (strncmp($app_prefix, $class, $app_len) === 0) {
        $relative_class = substr($class, $app_len);
        $file = $app_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
        return;
    }
    
    // Handle Database namespace
    $db_prefix = 'Database\\';
    $db_base_dir = __DIR__ . '/../database/';
    
    $db_len = strlen($db_prefix);
    if (strncmp($db_prefix, $class, $db_len) === 0) {
        $relative_class = substr($class, $db_len);
        $file = $db_base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
        return;
    }
});

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
