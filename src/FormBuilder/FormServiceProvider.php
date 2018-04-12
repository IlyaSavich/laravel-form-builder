<?php

namespace Savich\FormBuilder;

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

        $this->loadViewsFrom(__DIR__ . '/../../views', 'form-builder');

        $this->publish();
    }

    /**
     * Publish configs
     */
    private function publish()
    {
        $this->publishes([
            __DIR__ . '/../../config/form-builder.php' => config_path('form-builder.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../../views' => resource_path('views/vendor/form-builder'),
        ], 'views');
    }
}
