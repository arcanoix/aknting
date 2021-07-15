<?php

namespace Modules\Pos\BulkActions;

use App\Abstracts\BulkAction;
use App\Events\Document\DocumentCancelled;
use App\Jobs\Document\DeleteDocument;
use App\Models\Document\Document;
use Exception;

class Orders extends BulkAction
{
    public $model = Document::class;

    public $actions = [
        'cancelled' => [
            'name'       => 'general.cancel',
            'message'    => 'pos::bulk_actions.message.cancelled',
            'permission' => 'update-pos-orders',
        ],
        'delete'    => [
            'name'       => 'general.delete',
            'message'    => 'pos::bulk_actions.message.delete',
            'permission' => 'delete-pos-orders',
        ],
    ];

    public function cancelled($request)
    {
        $orders = $this->getSelectedRecords($request);

        foreach ($orders as $order) {
            event(new DocumentCancelled($order));
        }
    }

    public function destroy($request)
    {
        $orders = $this->getSelectedRecords($request);

        foreach ($orders as $order) {
            try {
                $this->dispatch(new DeleteDocument($order));
            } catch (Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
