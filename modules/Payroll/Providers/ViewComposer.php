<?php

namespace Modules\Payroll\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Modules\Payroll\Http\ViewComposers\AddPayrollTab;
use View;

class ViewComposer extends Provider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Add Payroll tab in employee details page
        View::composer(
            ['employees::employees.show'],
            AddPayrollTab::class
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
