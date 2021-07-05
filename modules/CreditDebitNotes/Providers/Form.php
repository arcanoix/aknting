<?php

namespace Modules\CreditDebitNotes\Providers;

use Form as Facade;
use Illuminate\Support\ServiceProvider as Provider;

class Form extends Provider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        Facade::component('creditDebitNotesSelectGroup', 'credit-debit-notes::partials.form.select_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required'], 'col' => 'col-md-6', 'group_class' => null
        ]);

        Facade::component('creditDebitNotesSelectAddNewGroup', 'credit-debit-notes::partials.form.select_add_new_group', [
            'name', 'text', 'icon', 'values', 'selected' => null, 'attributes' => ['required' => 'required', 'path' => ''], 'col' => 'col-md-6', 'group_class' => null
        ]);
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
