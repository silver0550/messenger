<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->defineCrud();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    public function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    public function defineCrud(): void
    {
        Route::macro('crud', function ($prefix, $controller, $name = null, $middleware = null) {
            Route::group([
                'prefix' => $prefix,
                'middleware' => $middleware,
                'as' => $name,
            ], function () use ($controller) {
                Route::get('/', [$controller, 'index'])->name('.index');
                Route::post('/', [$controller, 'store'])->name('.store');
                Route::get('/{id}', [$controller, 'show'])->name('.show');
                Route::put('/{id}', [$controller, 'update'])->name('.update');
                Route::delete('/{id}', [$controller, 'destroy'])->name('.destroy');
            });
        });
    }
}
