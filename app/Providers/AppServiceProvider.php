<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\custom;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191);

        Validator::extend('alpha_spaces', function ($attribute, $value) {

            // This will only accept alpha and spaces.
            return preg_match('/^[\pL\s-]+$/u', $value);

        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            /*$this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
            $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);*/
            $this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
            $this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);
        }
    }
}
