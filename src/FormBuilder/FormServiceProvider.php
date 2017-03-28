<?php

namespace App\Services\FormBuilder;

use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving(Form::class, function ($form, $app) {
            /* @var Form $form */
            $form->initializeRequest($app['request'], $app);
        });

        $this->publishes([
            __DIR__ . '/../../config/laravel-filter.php' => config_path('laravel-filter.php'),
        ], 'config');
    }
}
