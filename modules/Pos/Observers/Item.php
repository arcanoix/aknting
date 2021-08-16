<?php

namespace Modules\Pos\Observers;

use App\Abstracts\Observer;
use App\Models\Common\Item as Model;

class Item extends Observer
{
    public function saved(Model $item)
    {
        $all_attributes = $item->allAttributes;

        $barcode = $all_attributes['ean_upc_barcode'] ?? $all_attributes['barcode'] ?? null;

        if (!$barcode) {
            return;
        }

        $item->barcode()->updateOrCreate(
            ['company_id' => $item->company_id],
            ['code' => $barcode],
        );
    }

    public function deleted(Model $item)
    {
        $item->barcode()->delete();
    }
}
