<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
        // A "hot" file left in the public folder by "npm run dev" would point every page at a dev
        // server on the visitor's own machine. Only local development uses the dev server.
        if (! $this->app->environment('local')) {
            Vite::useHotFile(storage_path('framework/vite.hot'));
        }
    }
}
