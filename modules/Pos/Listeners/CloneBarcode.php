<?php

namespace Modules\Pos\Listeners;

class CloneBarcode
{
    public function handle($clone, $original)
    {
        $clone->barcode()->create([
            'company_id' => $clone->company_id,
            'code'       => $original->barcode->code,
        ]);
    }
}
