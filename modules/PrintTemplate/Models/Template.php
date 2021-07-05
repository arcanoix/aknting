<?php

namespace Modules\PrintTemplate\Models;

use App\Abstracts\Model;
use App\Traits\Media;
use Bkwld\Cloner\Cloneable;

class Template extends Model
{
    use Cloneable, Media; 

    protected $table = 'print_templates';
    protected $fillable = ['company_id', 'name', 'type', 'pagesize', 'enabled','printBackground'];
    

    public function Items()
    {
        return $this->hasMany('Modules\PrintTemplate\Models\TemplateItem','id','template_id');
    }

    public function getAttachmentAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('attachment')) {
            return $value;
        } elseif (!$this->hasMedia('attachment')) {
            return false;
        }

        return $this->getMedia('attachment')->last();
    }
}
