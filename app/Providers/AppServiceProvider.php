<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\KitchenCapacityService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(KitchenCapacityService::class, fn() => new
            KitchenCapacityService(
                capacity: (int) config('app.kitchen_capacity', 5),
                slotSeconds: (int) config('app.suggestion_slot_seconds', 300),
            ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
