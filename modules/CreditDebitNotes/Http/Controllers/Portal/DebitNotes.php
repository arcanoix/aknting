<?php

namespace Modules\CreditDebitNotes\Http\Controllers\Portal;

use App\Abstracts\Http\Controller;
use App\Models\Setting\Category;
use App\Traits\Currencies;
use App\Traits\DateTime;
use App\Traits\Documents;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\CreditDebitNotes\Events\DebitNotePrinting;
use Modules\CreditDebitNotes\Events\DebitNoteViewed;
use Modules\CreditDebitNotes\Models\DebitNote;
use Modules\CreditDebitNotes\Traits\Documents as CreditDebitNotesDocuments;
use App\Traits\Uploads;
use Illuminate\Support\Facades\URL;
use Throwable;

class DebitNotes extends Controller
{
    use DateTime, Currencies, Documents, CreditDebitNotesDocuments, Uploads;
// TODO: enable portal after upgrading to Akaunting 2.1
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
//        $debit_notes = DebitNote::with('contact', 'histories', 'items')
//            ->accrued()
//            ->where('contact_id', user()->contact->id)
//            ->collect(['debit_note_number'=> 'desc']);
//
//        $categories = collect(Category::income()->enabled()->orderBy('name')->pluck('name', 'id'));
//
//        $statuses = $this->getDebitNoteStatuses();
//
//        return view('credit-debit-notes::portal.debit_notes.index', compact('debit_notes', 'categories', 'statuses'));
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  DebitNote  $debit_note
     *
     * @return View
     */
    public function show(DebitNote $debit_note)
    {
//        $debit_note->load('bill');
//
//        event(new DebitNoteViewed($debit_note));
//
//        return view('credit-debit-notes::portal.debit_notes.show', compact('debit_note'));
    }

    /**
     * Print the debit note.
     *
     * @param  DebitNote  $debit_note
     *
     * @return string
     */
    public function printDebitNote(DebitNote $debit_note)
    {
//        $debit_note = $this->prepareDebitNote($debit_note);
//
//        return view($debit_note->template_path, compact('debit_note'));
    }

    /**
     * Download the PDF file of the debit note.
     *
     * @param DebitNote $debit_note
     *
     * @return Response
     * @throws Throwable
     */
    public function pdfDebitNote(DebitNote $debit_note)
    {
//        $debit_note = $this->prepareDebitNote($debit_note);
//
//        $currency_style = true;
//
//        $view = view($debit_note->template_path, compact('debit_note', 'currency_style'))->render();
//        $html = mb_convert_encoding($view, 'HTML-ENTITIES');
//
//        $pdf = \App::make('dompdf.wrapper');
//        $pdf->loadHTML($html);
//
//        //$pdf->setPaper('A4', 'portrait');
//
//        $file_name = 'debit_note_' . time() . '.pdf';
//
//        return $pdf->download($file_name);
    }

    /**
     * @param DebitNote $debit_note
     * @return DebitNote
     */
    protected function prepareDebitNote(DebitNote $debit_note)
    {
//        $debit_note->bill_number = $debit_note->bill->bill_number;
//
//        $debit_note->template_path = 'credit-debit-notes::debit_notes.print';
//
//        event(new DebitNotePrinting($debit_note));
//
//        return $debit_note;
    }

    /**
     * @param DebitNote $debit_note
     * @return View
     */
    public function signed(DebitNote $debit_note)
    {
//        if (empty($debit_note)) {
//            redirect()->route('login');
//        }
//
//        $debit_note->load('bill');
//
//        $print_action = URL::signedRoute('signed.credit-debit-notes.debit-notes.print', [$debit_note->id]);
//        $pdf_action = URL::signedRoute('signed.credit-debit-notes.debit-notes.pdf', [$debit_note->id]);
//
//        event(new DebitNoteViewed($debit_note));
//
//        return view(
//            'credit-debit-notes::portal.debit_notes.signed',
//            compact(
//                'debit_note',
//                'print_action',
//                'pdf_action'
//            )
//        );
    }
}
