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
        // Force HTTPS scheme on Vercel to prevent Mixed Content blocking
        if (env('VERCEL') || isset($_ENV['VERCEL'])) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
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
