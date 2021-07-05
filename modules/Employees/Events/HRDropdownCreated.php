<?php

namespace Modules\Employees\Events;

use App\Abstracts\Event;

class HRDropdownCreated extends Event
{
    public $menu;

    public function __construct($menu)
    {
        $this->menu = $menu;
    }
}
