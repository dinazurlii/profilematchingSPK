<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::middlewareGroup('checkLogin', [
        function ($request, $next) {
            if (!session()->has('user')) {
                return redirect()->route('login');
            }
            return $next($request);
        }
    ]);
    }
}
