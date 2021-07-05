<?php

namespace Modules\Employees\Widgets;

use App\Abstracts\Widget;
use Modules\Employees\Models\Employee;

class Profile extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function getDefaultName()
    {
        return trans('auth.profile');
    }

    public function show()
    {
        return $this->view('employees::widgets.profile', [
            'employee' => Employee::where('contact_id', user()->contact->id)->first(),
        ]);
    }
}
