<?php

namespace Modules\Pos\Http\ViewComposers;

use App\Models\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Pos\Traits\CustomFields;

class AddBarcodeFieldInItem
{
    use CustomFields;

    public function compose(View $view)
    {
        if ($this->isCustomFieldsActive()) {
            $custom_field = DB::table('custom_fields_fields')
                ->where([
                    'code'       => 'barcode',
                    'type_id'    => 4,
                    'enabled'    => 1,
                    'locations'  => 2,
                    'deleted_at' => null,
                ])
                ->first();

            if ($custom_field) {
                return;
            }
        }

        if (isset($view->getData()['item'])) {
            $item = $view->getData()['item'];
            $item->ean_upc_barcode = $item->barcode->code;

            $view->with(['item' => $item]);
        }

        $view->getFactory()->startPush('category_id_input_end', view('pos::partials.item.barcode'));
    }
}
