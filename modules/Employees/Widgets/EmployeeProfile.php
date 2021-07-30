<?php

namespace Modules\Employees\Widgets;

use App\Abstracts\Widget;
use App\Models\Common\Contact;

class EmployeeProfile extends Widget
{
    public $views = [
        'header' => 'partials.widgets.stats_header',
    ];

    public function getDefaultName(): string
    {
        return trans_choice('employees::general.employees', 1) . ' ' . trans('auth.profile');
    }

    public function show()
    {
        $contact = Contact::with('employee.position')->where('user_id', user_id())->first();

        return $this->view('employees::widgets.employee_profile', [
            'contact' => $contact,
        ]);
    }
}
