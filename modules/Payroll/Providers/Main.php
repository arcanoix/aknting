<?php

namespace Modules\Payroll\Providers;

use App\Traits\Modules;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Payroll\Models\PayCalendar\Employee as PayCalendarEmployee;
use Modules\Payroll\Models\RunPayroll\RunPayrollEmployee;

class Main extends ServiceProvider
{
    use Modules;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @var array
     */
    public function boot()
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->loadConfig();
        $this->registerDynamicRelations();
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
     * Register views.
     *
     * @return void
     */
    public function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'payroll');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'payroll');
    }

    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
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
            'admin.php'
        ];

        foreach ($routes as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }

    /**
     * Load config.
     *
     * @return void
     */
    public function loadConfig()
    {
        $replaceConfigs = ['search-string'];

        foreach ($replaceConfigs as $config) {
            Config::set($config, array_merge_recursive(
                Config::get($config),
                require __DIR__ . '/../Config/' . $config . '.php'
            ));
        }

//        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'credit-debit-notes');
    }

    public function registerDynamicRelations()
    {
        if ($this->moduleExists('employees')) {
            $model = 'Modules\Employees\Models\Employee';

            $model::resolveRelationUsing('run_payroll_employees', function ($employee) {
                return $employee->hasMany(RunPayrollEmployee::class, 'employee_id', 'id');
            });
            $model::resolveRelationUsing('pay_calendar_employees', function ($employee) {
                return $employee->hasMany(PayCalendarEmployee::class, 'employee_id', 'id');
            });
        }
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
