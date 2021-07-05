<?php

namespace Modules\Employees\Events;

use App\Abstracts\Event;

class DetermineRelationships extends Event
{
    public $rel;

    public function __construct(&$rel)
    {
        $this->rel = &$rel;
    }
}
