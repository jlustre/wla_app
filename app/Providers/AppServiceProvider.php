<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
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
        // utf8mb4 uses 4 bytes per character. A 255-char index is 1020 bytes, which
        // exceeds the 1000-byte MyISAM / 767-byte older InnoDB key limit on shared hosts.
        Schema::defaultStringLength(191);
    }
}
