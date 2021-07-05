<?php

namespace Modules\Employees\Events;

use App\Abstracts\Event;

class AddingHRDropdown extends Event
{
    public $show_dropdown;

    public function __construct(bool &$show_dropdown)
    {
        $this->show_dropdown = &$show_dropdown;
    }
}
