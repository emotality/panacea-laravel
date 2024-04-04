<?php

namespace Emotality\Panacea;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class PanaceaMobileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(PanaceaMobileAPI::class, function () {
            return new PanaceaMobileAPI();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/panacea.php' => App::configPath('panacea.php'),
            ], 'config');
        }
    }
}
