<?php

namespace App\Providers;

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
        // Dynamic serverless fallback for Vercel when no database is connected yet
        if (env('VERCEL') || isset($_ENV['VERCEL'])) {
            if (!env('POSTGRES_URL') && !env('DB_HOST')) {
                config(['session.driver' => 'cookie']);
                config(['cache.default' => 'file']);
                config(['queue.default' => 'sync']);
                config(['database.default' => 'sqlite']);
                config(['database.connections.sqlite.database' => ':memory:']);
            }
        }
    }
}
