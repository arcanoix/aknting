<?php

namespace Modules\CreditDebitNotes\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Modules\CreditDebitNotes\Http\ViewComposers\AddOriginalContactIdField;
use Modules\CreditDebitNotes\Http\ViewComposers\Bill\ShowCreateDebitNoteMenuItem;
use Modules\CreditDebitNotes\Http\ViewComposers\Contact\AddCreditNotesStatistics;
use Modules\CreditDebitNotes\Http\ViewComposers\CreditNote\AddCreditCustomerAccountField;
use Modules\CreditDebitNotes\Http\ViewComposers\CreditNote\AddInvoiceSelectionField;
use Modules\CreditDebitNotes\Http\ViewComposers\CreditNote\PrefillCreditNote;
use Modules\CreditDebitNotes\Http\ViewComposers\CreditNote\ShowInvoiceNumber;
use Modules\CreditDebitNotes\Http\ViewComposers\DebitNote\AddBillSelectionField;
use Modules\CreditDebitNotes\Http\ViewComposers\DebitNote\PrefillDebitNote;
use Modules\CreditDebitNotes\Http\ViewComposers\DebitNote\ShowBillNumber;
use Modules\CreditDebitNotes\Http\ViewComposers\Invoice\ReduceAmountDue;
use Modules\CreditDebitNotes\Http\ViewComposers\Invoice\ShowAppliedCredits;
use Modules\CreditDebitNotes\Http\ViewComposers\Invoice\ShowCreateCreditNoteButton;
use Modules\CreditDebitNotes\Http\ViewComposers\Invoice\ShowCreditsTransactions;
use Modules\CreditDebitNotes\Http\ViewComposers\Invoice\UseCredits;
use Modules\CreditDebitNotes\Http\ViewComposers\ShowCustomTransactionsTable;
use Modules\CreditDebitNotes\Http\ViewComposers\Widget\CreditsInTotalIncome;
use Modules\CreditDebitNotes\Http\ViewComposers\Widget\CreditsInTotalProfit;
use Modules\CreditDebitNotes\Http\ViewComposers\Widget\DebitNotesInTotalExpenses;
use Modules\CreditDebitNotes\Http\ViewComposers\Widget\DebitNotesInTotalProfit;
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
        // Prefill a credit note when creating from an invoice
        View::composer(
            [
                'components.documents.form.metadata',
                'components.documents.form.advanced',
            ],
            PrefillCreditNote::class
        );

        // Show an invoice selection in a credit note
        View::composer(
            ['components.documents.form.metadata'],
            AddInvoiceSelectionField::class
        );

        // Show "Credit Customer Account" toggle in a credit note
        View::composer(
            ['components.documents.form.metadata'],
            AddCreditCustomerAccountField::class
        );

        // Prefill a debit note when creating from a bill
        View::composer(
            [
                'components.documents.form.metadata',
                'components.documents.form.advanced',
            ],
            PrefillDebitNote::class
        );

        // Show a bill selection in a debit note
        View::composer(
            ['components.documents.form.metadata'],
            AddBillSelectionField::class
        );

        // Add a hidden 'original_contact_id' field
        View::composer(['components.documents.form.metadata'], AddOriginalContactIdField::class);

        // Show a custom transactions table in credit and debit notes
        View::composer(['components.documents.show.transactions'], ShowCustomTransactionsTable::class);

        // Show an invoice number in a credit note
        View::composer(
            [
                'credit-debit-notes::credit_notes.show',
                'credit-debit-notes::credit_notes.print_default',
                'credit-debit-notes::credit_notes.print_classic',
                'credit-debit-notes::credit_notes.print_modern',
                'credit-debit-notes::portal.credit_notes.show',
                'credit-debit-notes::portal.credit_notes.signed',
            ],
            ShowInvoiceNumber::class
        );

        // Show a bill number in a debit note
        View::composer(
            [
                'credit-debit-notes::debit_notes.show',
                'credit-debit-notes::debit_notes.print',
//                'credit-debit-notes.portal::debit_notes.signed',
//                'credit-debit-notes.portal::debit_notes.show',
            ],
            ShowBillNumber::class
        );

        // Show "Create Credit Note" button in an invoice
        View::composer(
            ['sales.invoices.show'],
            ShowCreateCreditNoteButton::class
        );

        // Allow using credits in invoices
        View::composer(
            ['sales.invoices.show'],
            UseCredits::class
        );

        // Show credits transactions table in an invoice
        View::composer(
            ['sales.invoices.show'],
            ShowCreditsTransactions::class
        );

        // Show applied credits in an invoice
        View::composer(
            [
                'sales.invoices.show',
                'sales.invoices.print_default',
                'sales.invoices.print_classic',
                'sales.invoices.print_modern',
                'portal.invoices.signed',
                'portal.invoices.show',
            ],
            ShowAppliedCredits::class
        );

        // Reduce amount due when adding a payment in an invoice
        View::composer(
            ['modals.documents.payment'],
            ReduceAmountDue::class
        );

        // Show "Create Debit Note" button in a bill
        View::composer(
            ['purchases.bills.show'],
            ShowCreateDebitNoteMenuItem::class
        );

        // Add credit notes statistics in a customer page
        View::composer(
            ['sales.customers.show'],
            AddCreditNotesStatistics::class
        );

        // Take into account credits in total income widget
        View::composer(
            ['widgets.total_income'],
            CreditsInTotalIncome::class
        );

        // Take into account credits in total profit widget
        View::composer(
            ['widgets.total_profit'],
            CreditsInTotalProfit::class
        );

        // Take into account debit notes in total expenses widget
        View::composer(
            ['widgets.total_expenses'],
            DebitNotesInTotalExpenses::class
        );

        // Take into account debit notes in total profit widget
        View::composer(
            ['widgets.total_profit'],
            DebitNotesInTotalProfit::class
        );

        // Add text for a credit note items table.
//        View::composer(
//            [
//                'credit-debit-notes::credit_notes.*',
//                'credit-debit-notes::portal.credit_notes.show',
//            ],
//            'Modules\CreditDebitNotes\Http\ViewComposers\CreditNote\TextOverride'
//        );
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
