<?php

namespace Modules\CreditDebitNotes\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Permissions;
use App\Traits\Transactions;
use Artisan;

class FinishInstallation
{
    use Permissions, Transactions;

    public $alias = 'credit-debit-notes';

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != $this->alias) {
            return;
        }

        $this->updatePermissions();

        $this->setDefaultSettings();

        $this->addCustomTransactionTypes();

        $this->callSeeds();
    }

    protected function updatePermissions()
    {
        $this->attachPermissionsToAdminRoles([
            $this->alias . '-credit-notes' => 'c,r,u,d',
            $this->alias . '-debit-notes' => 'c,r,u,d',
            $this->alias . '-credits-transactions' => 'd',
            $this->alias . '-settings-credit-note' => 'r,u',
            $this->alias . '-settings-debit-note' => 'r,u',
        ]);

        $this->attachPermissionsToPortalRoles([
            $this->alias . '-portal-credit-notes'  => 'r',
        ]);
    }

    protected function setDefaultSettings()
    {
        setting()->set([
            'credit-debit-notes.credit_note.title' => trans_choice('credit-debit-notes::general.credit_notes', 1),
        ]);

        setting()->save();
    }

    protected function addCustomTransactionTypes()
    {
        $this->addExpenseType('credit_note_refund');
        $this->addIncomeType('debit_note_refund');
    }

    protected function callSeeds()
    {
        Artisan::call('company:seed', [
            'company' => company_id(),
            '--class' => 'Modules\CreditDebitNotes\Database\Seeds\CreditDebitNotesDatabaseSeeder',
        ]);
    }
}
