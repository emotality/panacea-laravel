<?php

namespace Emotality\Panacea;

use Illuminate\Support\ServiceProvider;

class PanaceaMobileServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/panacea.php' => config_path('panacea.php'),
        ], 'config');
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        //
    }
}
