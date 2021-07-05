<?php

namespace Modules\CreditDebitNotes\Traits;

use App\Events\Document\DocumentPrinting;
use Illuminate\Support\Collection;

trait Documents
{
    private function getDebitNoteStatuses(): Collection
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
            $item->name = trans('credit-debit-notes::debit_notes.statuses.' . $code);

            return $item;
        });
    }

    public function storeCreditNotePdfAndGetPath($credit_note): string
    {
        event(new DocumentPrinting($credit_note));

//        unset($credit_note->invoice_number);

        $view = view($credit_note->template_path, ['credit_note' => $credit_note])->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getDocumentFileName($credit_note);

        $pdf_path = storage_path('app/temp/' . $file_name);

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        return $pdf_path;
    }

    public function storeDebitNotePdfAndGetPath($debit_note): string
    {
        event(new DocumentPrinting($debit_note));

//        unset($debit_note->bill_number);

        $view = view($debit_note->template_path, ['debit_note' => $debit_note])->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getDocumentFileName($debit_note);

        $pdf_path = storage_path('app/temp/' . $file_name);

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        return $pdf_path;
    }
}
