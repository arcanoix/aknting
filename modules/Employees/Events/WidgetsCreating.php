<?php

namespace Modules\Employees\Events;

class WidgetsCreating
{
    public $dashboard_items;

    public function __construct($dashboard_items)
    {
        $this->dashboard_items = $dashboard_items;
    }
}
