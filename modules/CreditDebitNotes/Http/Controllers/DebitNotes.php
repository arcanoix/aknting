<?php

namespace Modules\CreditDebitNotes\Http\Controllers;

use App\Abstracts\Http\Controller;
use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentPrinting;
use App\Events\Document\DocumentSent;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\DuplicateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Traits\Documents;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Modules\CreditDebitNotes\Http\Requests\DebitNote as Request;
use Modules\CreditDebitNotes\Models\DebitNote as Document;

class DebitNotes extends Controller
{
    use Documents;

    public function index()
    {
        $debit_notes = Document::with('contact', 'transactions')->collect(['issued_at' => 'desc']);

        return view('credit-debit-notes::debit_notes.index', compact('debit_notes'));
    }

    public function create()
    {
        $bill_items = collect([]);
        if ($bill_id = request()->query('bill', null)) {
            $bill = \App\Models\Document\Document::bill()->findOrFail($bill_id);
            $bill_items = $bill->items;
        }

        return view('credit-debit-notes::debit_notes.create', compact('bill_items'));
    }

    public function store(Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new CreateDocument($request));

        if ($response['success']) {
            $response['redirect'] = route('credit-debit-notes.debit-notes.show', $response['data']->id);

            $message = trans('messages.success.added', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('credit-debit-notes.debit-notes.create');

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function show(Document $debit_note)
    {
        // Get Debit Note Totals
        foreach ($debit_note->totals_sorted as $debit_note_total) {
            $debit_note->{$debit_note_total->code} = $debit_note_total->amount;
        }

        $currency_code = $debit_note->currency_code;

        $total = money($debit_note->total, $currency_code, true)->format();
        $debit_note->grand_total = money($total, $currency_code)->getAmount();

        foreach ($debit_note->transactions as $transaction) {
            $transaction->type = trans('credit-debit-notes::debit_notes.refund');
        }

        return view('credit-debit-notes::debit_notes.show', compact('debit_note'));
    }

    public function edit(Document $debit_note)
    {
        $debit_note->vendor_bills = $debit_note->contact->bills()
            ->whereIn('status', ['received', 'partial', 'paid'])
            ->pluck('document_number', 'id');

        return view('credit-debit-notes::debit_notes.edit', compact('debit_note'));
    }

    public function update(Document $debit_note, Request $request): JsonResponse
    {
        $response = $this->ajaxDispatch(new UpdateDocument($debit_note, $request));

        if ($response['success']) {
            $response['redirect'] = route('credit-debit-notes.debit-notes.show', $response['data']->id);

            $message = trans('messages.success.updated', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]);

            flash($message)->success();
        } else {
            $response['redirect'] = route('credit-debit-notes.debit-notes.edit', $debit_note->id);

            $message = $response['message'];

            flash($message)->error()->important();
        }

        return response()->json($response);
    }

    public function destroy(Document $debit_note): JsonResponse
    {
        $response = $this->ajaxDispatch(new DeleteDocument($debit_note));

        $response['redirect'] = route('credit-debit-notes.debit-notes.index');

        if ($response['success']) {
            $message = trans('messages.success.deleted', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]);

            flash($message)->success();
        } else {
            flash($response['message'])->error()->important();
        }

        return response()->json($response);
    }

    public function duplicate(Document $debit_note): RedirectResponse
    {
        $clone = $this->dispatch(new DuplicateDocument($debit_note));

        $message = trans('messages.success.duplicated', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]);

        flash($message)->success();

        return redirect()->route('credit-debit-notes.debit-notes.edit', $clone->id);
    }

//    /**
//     * Export the specified resource.
//     *
//     * @return Response
//     */
//    public function export()
//    {
//        // TODO: implement debit notes export
//    }

    public function markSent(Document $debit_note): RedirectResponse
    {
        event(new DocumentSent($debit_note));

        $message = trans('documents.messages.marked_sent', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    public function markCancelled(Document $debit_note): RedirectResponse
    {
        event(new DocumentCancelled($debit_note));

        $message = trans('documents.messages.marked_cancelled', ['type' => trans_choice('credit-debit-notes::general.debit_notes', 1)]);

        flash($message)->success();

        return redirect()->back();
    }

    public function printDebitNote(Document $debit_note): string
    {
        event(new DocumentPrinting($debit_note));

        $view = view($debit_note->template_path, compact('debit_note'));

        return mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');
    }

    public function pdfDebitNote(Document $debit_note): Response
    {
        event(new DocumentPrinting($debit_note));

        $currency_style = true;

        $view = view($debit_note->template_path, compact('debit_note', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getDocumentFileName($debit_note);

        return $pdf->download($file_name);
    }
}
