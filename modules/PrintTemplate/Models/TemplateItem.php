<?php

namespace Modules\PrintTemplate\Models;

use App\Abstracts\Model;

class TemplateItem extends Model
{
    public $timestamps = false;
    protected $table = 'print_template_item';
    
    protected $fillable = ['company_id', 'template_id', 'item_id', 'attr'];
}
