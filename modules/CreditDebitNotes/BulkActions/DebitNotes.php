<?php

namespace Modules\CreditDebitNotes\BulkActions;

use App\Abstracts\BulkAction;
use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentSent;
use App\Jobs\Document\DeleteDocument;
use App\Models\Document\Document;
use Exception;

class DebitNotes extends BulkAction
{
    public $model = Document::class;

    public $actions = [
        'sent' => [
            'name' => 'credit-debit-notes::debit_notes.mark_sent',
            'message' => 'credit-debit-notes::bulk_actions.message.sent',
            'permission' => 'update-credit-debit-notes-debit-notes',
        ],
        'cancelled' => [
            'name' => 'general.cancel',
            'message' => 'credit-debit-notes::bulk_actions.message.cancelled',
            'permission' => 'update-credit-debit-notes-debit-notes',
        ],
        'delete' => [
            'name' => 'general.delete',
            'message' => 'credit-debit-notes::bulk_actions.message.delete',
            'permission' => 'delete-credit-debit-notes-debit-notes',
        ],
        // TODO: implement export
//        'export' => [
//            'name' => 'general.export',
//            'message' => 'bulk_actions.message.export',
//            'type' => 'download',
//        ],
    ];

    public function sent($request)
    {
        $debit_notes = $this->getSelectedRecords($request);

        foreach ($debit_notes as $debit_note) {
            event(new DocumentSent($debit_note));
        }
    }

    public function cancelled($request)
    {
        $debit_notes = $this->getSelectedRecords($request);

        foreach ($debit_notes as $debit_note) {
            event(new DocumentCancelled($debit_note));
        }
    }

    public function destroy($request)
    {
        $debit_notes = $this->getSelectedRecords($request);

        foreach ($debit_notes as $debit_note) {
            try {
                $this->dispatch(new DeleteDocument($debit_note));
            } catch (Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function export($request)
    {
        // TODO: implement export
//        $selected = $this->getSelectedInput($request);
//
//        return \Excel::download(new Export($selected), \Str::filename(trans_choice('credit-debit-notes::general.debit_notes', 2)) . '.xlsx');
    }
}
