<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS untuk semua URL yang di-generate Laravel
        if (app()->environment('production') || request()->isSecure()) {
            URL::forceScheme('https');
        }
        
        // Alternative: force HTTPS selalu (jika mau lebih simple)
        // URL::forceScheme('https');
    }
}