<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
{
    // Register custom Blade directive for detecting device type
    Blade::if('detect', function ($device) {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            
            // Check for mobile device user agent
            if (preg_match('/(Mobile|Android|Tablet|iPhone|iPod|BlackBerry|Windows Phone)/i', $userAgent)) {
                if ($device === 'mobile') {
                    return true; // Mobile content
                }
            } else {
                if ($device === 'desktop') {
                    return true; // Desktop content
                }
            }
        }
        return false; // Default to false if no match
    });
}
    
}
