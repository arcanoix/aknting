<?php

namespace Modules\CreditDebitNotes\Providers;

use App\Models\Banking\Transaction;
use App\Models\Document\Document;
use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as Provider;
use Modules\CreditDebitNotes\Models\CreditNote;
use Modules\CreditDebitNotes\Models\CreditsTransaction;
use Modules\CreditDebitNotes\Models\DebitNote;

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
        $this->loadViewComponents();
        $this->loadTranslations();
        $this->loadMigrations();
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
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'credit-debit-notes');
    }

    /**
     * Load view components.
     *
     * @return void
     */
    public function loadViewComponents()
    {
        Blade::componentNamespace('Modules\\CreditDebitNotes\\View\\Components', 'credit-debit-notes');
    }

    /**
     * Load translations.
     *
     * @return void
     */
    public function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'credit-debit-notes');
    }

    /**
     * Load migrations.
     *
     * @return void
     */
    public function loadMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Load config.
     *
     * @return void
     */
    public function loadConfig()
    {
        $replaceConfigs = ['columnsortable', 'setting', 'search-string', 'type'];

        foreach ($replaceConfigs as $config) {
            Config::set($config, array_merge_recursive(
                Config::get($config),
                require __DIR__ . '/../Config/' . $config . '.php'
            ));
        }

//        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'credit-debit-notes');
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
            'portal.php',
            'signed.php',
        ];

        foreach ($routes as $route) {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/' . $route);
        }
    }

    /**
     * Register dynamic relations.
     *
     * @return void
     */
    public function registerDynamicRelations()
    {
        Document::resolveRelationUsing('credit_notes', function ($invoiceModel) {
            return $invoiceModel->belongsToMany(CreditNote::class, 'credit_debit_notes_credit_note_details', 'invoice_id', 'document_id');
        });
        Document::resolveRelationUsing('debit_notes', function ($billModel) {
            return $billModel->belongsToMany(DebitNote::class, 'credit_debit_notes_debit_note_details', 'bill_id', 'document_id');
        });
    }

    /**
     * Register observers.
     *
     * @return void
     */
    public function registerObservers()
    {
        CreditNote::observe('Modules\CreditDebitNotes\Observers\Document');
        DebitNote::observe('Modules\CreditDebitNotes\Observers\Document');
        Document::observe('Modules\CreditDebitNotes\Observers\Invoice');
        CreditsTransaction::observe('Modules\CreditDebitNotes\Observers\CreditsTransaction');
        Transaction::observe('Modules\CreditDebitNotes\Observers\Transaction');
    }
}
