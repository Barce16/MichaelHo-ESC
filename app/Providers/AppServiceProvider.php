<?php

namespace App\Providers;

use App\Services\SmsService;
use App\Services\EventSmsNotifier;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService();
        });

        $this->app->singleton(EventSmsNotifier::class, function ($app) {
            return new EventSmsNotifier($app->make(SmsService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
