<?php

namespace Modules\CreditDebitNotes\Http\ViewComposers\Contact;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Models\CreditNote;

class AddCreditNotesStatistics
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        if (!user()->can('read-credit-debit-notes-credit-notes')) {
            return;
        }

        $limit = request('limit', setting('default.list_limit', '25'));
        $viewData = $view->getData();
        $customer = $viewData['customer'];
        $credit_notes = CreditNote::with('credits_transactions')
            ->contact($customer->id)
            ->get();
        $credit_notes = $this->paginate($credit_notes->sortByDesc('issued_at'), $limit);


        $view->getFactory()->startPush(
            'customer_transactions_count_end',
            view('credit-debit-notes::partials.customer.credit_notes_count', ['credit_notes_count' => $credit_notes->total()])
        );
        $view->getFactory()->startPush(
            'customer_transactions_tab_end',
            view('credit-debit-notes::partials.customer.credit_notes_tab')
        );
        $view->getFactory()->startPush(
            'customer_transactions_content_end',
            view(
                'credit-debit-notes::partials.customer.credit_notes_content',
                [
                    'credit_notes' => $credit_notes,
                    'limits' => ['10' => '10', '25' => '25', '50' => '50', '100' => '100'],
                ]
            )
        );
    }

    /**
     * Generate a pagination collection.
     *
     * @param array|Collection $items
     * @param int $perPage
     * @param int|null $page
     * @param array $options
     *
     * @return LengthAwarePaginator
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $perPage = $perPage ?: request('limit', setting('default.list_limit', '25'));

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
