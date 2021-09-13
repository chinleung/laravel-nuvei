<?php

namespace ChinLeung\Nuvei;

use Illuminate\Support\ServiceProvider;

class NuveiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('nuvei.php'),
            ], 'config');
        }

        $this->app->bind(Client::class, static function () {
            return new Client(
                config('nuvei.merchant_id'),
                config('nuvei.terminal.id'),
                config('nuvei.terminal.secret'),
                config('nuvei.demo')
            );
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'nuvei');
    }
}
