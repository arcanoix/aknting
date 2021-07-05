<?php

namespace Modules\CreditDebitNotes\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Events\Document\DocumentPrinting;
use App\Events\Document\DocumentViewed;
use App\Models\Setting\Category;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Documents;
use App\Traits\Uploads;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Http\Requests\Portal\CreditNoteShow as Request;
use Modules\CreditDebitNotes\Models\CreditNote as Document;
use Illuminate\Support\Facades\URL;
use Throwable;

class CreditNotes extends Controller
{
    use DateTime, Currencies, Documents, Uploads;

    public function index()
    {
        $credit_notes = Document::with('contact', 'histories', 'items', 'payments')
            ->accrued()
            ->where('contact_id', user()->contact->id)
            ->collect(['document_number'=> 'desc']);

        $categories = collect(Category::income()->enabled()->orderBy('name')->pluck('name', 'id'));

        $statuses = $this->getDocumentStatuses(Document::TYPE);

        return $this->response('credit-debit-notes::portal.credit_notes.index', compact('credit_notes', 'categories', 'statuses'));
    }

    public function show(Document $credit_note, Request $request)
    {
        event(new DocumentViewed($credit_note));

        return view('credit-debit-notes::portal.credit_notes.show', compact('credit_note'));
    }

    public function printCreditNote(Document $credit_note, Request $request)
    {
        $credit_note = $this->prepareCreditNote($credit_note);

        return view($credit_note->template_path, compact('credit_note'));
    }

    public function pdfCreditNote(Document $credit_note, Request $request)
    {
        $credit_note = $this->prepareCreditNote($credit_note);

        $currency_style = true;

        $view = view($credit_note->template_path, compact('credit_note', 'currency_style'))->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES');

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($html);

        //$pdf->setPaper('A4', 'portrait');

        $file_name = 'credit_note_' . time() . '.pdf';

        return $pdf->download($file_name);
    }

    protected function prepareCreditNote(Document $credit_note): Document
    {
        $credit_note->template_path = 'credit-debit-notes::credit_notes.print_' . setting('credit-debit-notes.credit_note.template' ,'default');

        event(new DocumentPrinting($credit_note));

        return $credit_note;
    }

    public function signed(Document $credit_note)
    {
        if (empty($credit_note)) {
            redirect()->route('login');
        }

        $invoice_signed_url = $credit_note->invoice_id
            ? URL::signedRoute('signed.invoices.show', [$credit_note->invoice_id])
            : '';

        $print_action = URL::signedRoute('signed.credit-debit-notes.credit-notes.print', [$credit_note->id]);
        $pdf_action = URL::signedRoute('signed.credit-debit-notes.credit-notes.pdf', [$credit_note->id]);

        event(new DocumentViewed($credit_note));

        return view(
            'credit-debit-notes::portal.credit_notes.signed',
            compact(
                'credit_note',
                'print_action',
                'pdf_action',
                'invoice_signed_url'
            )
        );
    }

    public function getDocumentStatuses(string $type): Collection
    {
        $list = [
            'draft',
            'sent',
            'viewed',
            'cancelled',
        ];

        return collect($list)->each(function ($code) {
            $item = new \stdClass();
            $item->code = $code;
            $item->name = trans('credit-debit-notes::credit_notes.statuses.' . $code);

            return $item;
        });
    }
}
