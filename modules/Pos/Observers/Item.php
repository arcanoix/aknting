<?php

namespace Modules\Pos\Observers;

use App\Abstracts\Observer;
use App\Models\Common\Item as Model;

class Item extends Observer
{
    public function saved(Model $item)
    {
        $all_attributes = $item->allAttributes;

        if (isset($all_attributes['ean_upc_barcode'])) {
            $item->barcode()->updateOrCreate(
                ['company_id' => $item->company_id],
                ['code' => $all_attributes['ean_upc_barcode']],
            );
        }
    }

    public function deleted(Model $item)
    {
        $item->barcode()->delete();
    }
}
