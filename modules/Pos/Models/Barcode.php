<?php

namespace Modules\Pos\Models;

use App\Abstracts\Model;

class Barcode extends Model
{
    protected $table = 'pos_barcodes';

    protected $fillable = ['company_id', 'item_id', 'code'];
}
