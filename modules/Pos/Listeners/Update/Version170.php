<?php

namespace Modules\Pos\Listeners\Update;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Item;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Pos\Models\Barcode;
use Modules\Pos\Traits\CustomFields;

class Version170 extends Listener
{
    use CustomFields;

    const ALIAS = 'pos';

    const VERSION = '1.7.0';

    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->runMigrations();

        $this->replicateCustomFieldsBarcodes();
    }

    private function runMigrations()
    {
        Artisan::call('module:migrate', ['alias' => self::ALIAS, '--force' => true]);
    }

    private function replicateCustomFieldsBarcodes()
    {
        if (!$this->isCustomFieldsActive()) {
            return;
        }

        $custom_field = DB::table('custom_fields_fields')
            ->where([
                'code'       => 'barcode',
                'type_id'    => 4,
                'enabled'    => 1,
                'locations'  => 2,
                'deleted_at' => null,
            ])
            ->first();

        if (!$custom_field) {
            return;
        }

        $custom_barcodes = DB::table('custom_fields_field_values')
            ->where([
                'field_id'    => $custom_field->id,
                'type_id'     => 4,
                'type'        => 'text',
                'location_id' => 2,
                'model_type'  => Item::class,
                'deleted_at'  => null,
            ])
            ->cursor();

        foreach ($custom_barcodes as $custom_barcode) {
            Barcode::create([
                'company_id' => $custom_barcode->company_id,
                'item_id'    => $custom_barcode->model_id,
                'code'       => $custom_barcode->value,
            ]);
        }
    }
}
