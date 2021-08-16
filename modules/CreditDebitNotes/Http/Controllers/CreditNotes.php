<?php

namespace Modules\CreditDebitNotes\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentPrinting;
use App\Events\Document\DocumentSent;
use Modules\CreditDebitNotes\Http\Requests\CreditNote as Request;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Traits\Documents;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Modules\CreditDebitNotes\Models\CreditNote as Document;
use Modules\CreditDebitNotes\Notifications\CreditNote as Notification;
use Throwable;

class CreditNotes extends Controller
{
    use Documents;

    public function index()
    {
        $credit_notes = Document::with('contact', 'transactions')->collect(['document_number' => 'desc']);

        return $this->response('credit-debit-notes::credit_notes.index', compact('credit_notes'));
    }

    public function create()
    {
        $invoice_items = collect([]);
        if ($invoice_id = request()->query('invoice', null)) {
            $invoice = \App\Models\Document\Document::invoice()->findOrFail($invoice_id);
            $invoice_items = $invoice->items;
        }

        return view('credit-debit-notes::credit_notes.create', compact('invoice_items'));
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreateDocument($request));

        if ($response['success']) {
            $response['redirect'] = route('credit-debit-notes.credit-notes.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('credit-debit-notes.credit-notes.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function show(Document $credit_note)
    {
        // Get Credit Note Totals
        foreach ($credit_note->totals_sorted as $credit_note_total) {
            $credit_note->{$credit_note_total->code} = $credit_note_total->amount;
        }

        $currency_code = $credit_note->currency_code;

        $total = money($credit_note->total, $currency_code, true)->format();
        $credit_note->grand_total = money($total, $currency_code)->getAmount();

        if ($credit_note->credit_customer_account) {
            $credit_note->setAttribute('transactions', $credit_note->credits_transactions);
        }
        foreach ($credit_note->transactions as $transaction) {
            $transaction->type = $credit_note->credit_customer_account ? trans('credit-debit-notes::credit_notes.credit') : trans('credit-debit-notes::credit_notes.refund');
        }

        return view('credit-debit-notes::credit_notes.show', compact('credit_note'));
    }

    public function edit(Document $credit_note)
    {
        $credit_note->customer_invoices = $credit_note->contact->invoices()
            ->whereIn('status', ['sent', 'partial', 'paid'])
            ->pluck('document_number', 'id');

        return view('credit-debit-notes::credit_notes.edit', compact('credit_note'));
    }

    public function update(Document $credit_note, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdateDocument($credit_note, $request));

        if ($response['success']) {
            $response['redirect'] = route('credit-debit-notes.credit-notes.show', $response['data']->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('credit-debit-notes.credit-notes.edit', $credit_note->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function destroy(Document $credit_note): JsonResponse
    {
        $response = $this->ajaxDispatch(new DeleteDocument($credit_note));

        $response['redirect'] = route('credit-debit-notes.credit-notes.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]);

            flash($message)->success();
        } else {
            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function duplicate(Document $credit_note): RedirectResponse
    {
        $clone = $this->dispatch(new DuplicateDocument($credit_note));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]);

        flash($message)->success();

        return redirect()->route('credit-debit-notes.credit-notes.edit', $clone->id);
    }

    /**
     * Export the specified resource.
     *
     * @return Response
     */
    public function export()
    {
        // TODO: implement credit notes export
    }

    public function markSent(Document $credit_note): RedirectResponse
    {
        event(new DocumentSent($credit_note));

        $message = trans('documents.messages.marked_sent', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    public function markCancelled(Document $credit_note): RedirectResponse
    {
        event(new DocumentCancelled($credit_note));

        $message = trans('documents.messages.marked_cancelled', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    /**
     * Sent by email the PDF file of the credit note.
     *
     * @param Document $credit_note
     *
     * @return RedirectResponse
     * @throws Throwable
     */
    public function emailCreditNote(Document $credit_note)
    {
        if (empty($credit_note->contact_email)) {
            return redirect()->back();
        }

        // Notify the customer
        $credit_note->contact->notify(new Notification($credit_note, 'credit_note_new_customer', true));

        event(new DocumentSent($credit_note));

        flash(trans('documents.messages.email_sent', ['type' => trans_choice('credit-debit-notes::general.credit_notes', 1)]))->success();

        return redirect()->back();
    }

    public function printCreditNote(Document $credit_note): string
    {
        event(new DocumentPrinting($credit_note));

        $view = view($credit_note->template_path, compact('credit_note'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    public function pdfCreditNote(Document $credit_note)
    {
        event(new DocumentPrinting($credit_note));

        $currency_style = true;

        $view = view($credit_note->template_path, compact('credit_note', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = $this->getDocumentFileName($credit_note);

        return $pdf->download($file_name);
    }
}
