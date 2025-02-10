<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\ServiceProvider;

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
        // $settings = Settings::first();
        // if ($settings) {
        //     if (isset($settings->companyName) && $settings->companyName) {
        //         config('app.name', $settings->companyName);
        //     }
        // }
    }
}
