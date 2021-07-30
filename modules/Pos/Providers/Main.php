<?php

namespace Modules\Pos\Providers;

use App\Models\Common\Item;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as Provider;
use Modules\Pos\Models\Barcode;

class Main extends Provider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadConfig();
        $this->registerDynamicRelations();
        $this->registerObservers();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutes();
    }

    /**
     * Load views.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'pos');
    }

    /**
     * Load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'pos');
    }

    public function loadConfig()
    {
        $replaceConfigs = ['setting', 'search-string', 'type'];

        foreach ($replaceConfigs as $config) {
            Config::set($config, array_merge_recursive(
                Config::get($config),
                require __DIR__ . '/../Config/' . $config . '.php'
            ));
        }

//        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'expenses');
    }

    public function registerDynamicRelations()
    {
        Item::resolveRelationUsing('barcode', function ($item) {
            return $item->hasOne(Barcode::class, 'item_id', 'id')
                ->withDefault(['code' => '']);
        });
    }
    public function registerObservers()
    {
        Item::observe('Modules\Pos\Observers\Item');
    }

    /**
     * Load routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        if (app()->routesAreCached()) {
            return;
        }

        $routes = [
            'admin.php',
        ];

        foreach ($routes as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }
}
