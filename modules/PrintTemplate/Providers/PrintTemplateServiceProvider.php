<?php

namespace Modules\PrintTemplate\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class PrintTemplateServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerMigrations();
        $this->registerViewComposers();
    }

    /**
     * Load routes.
     *
     * @return void
     */
    public function registerRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../Routes/routes.php');
     
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();
    }

    /**
     * Register View Composers.
     *
     * @return void
     */
    protected function registerViewComposers()
    {
        View::composer('sales.invoices.show', 'Modules\PrintTemplate\Http\ViewComposers\Invoice');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('print-template.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'print-template'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/print-template');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/print-template';
        }, \Config::get('view.paths')), [$sourcePath]), 'print-template');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/print-template');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'print-template');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'print-template');
        }
    }

    public function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

   

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
